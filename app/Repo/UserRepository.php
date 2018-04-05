<?php namespace BB\Repo;

use BB\Entities\User;
use Carbon\Carbon;

class UserRepository extends DBRepository
{

    /**
     * @var User
     */
    protected $model;
    /**
     * @var AddressRepository
     */
    private $addressRepository;
    /**
     * @var ProfileDataRepository
     */
    private $profileDataRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    public function __construct(User $model, AddressRepository $addressRepository, ProfileDataRepository $profileDataRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->model = $model;
        $this->perPage = 150;
        $this->addressRepository = $addressRepository;
        $this->profileDataRepository = $profileDataRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function getBillableActive()
    {
        return $this->model->active()->notSpecialCase()->get();
    }

    public function getPaginated(array $params)
    {
        $model = $this->model->with('roles')->with('profile');

        if ($params['showLeft']) {
            $model = $model->where('status', 'left');
        } else {
            $model = $model->where('status', '!=', 'left');
        }

        if ($this->isSortable($params)) {
            return $model->orderBy($params['sortBy'], $params['direction'])->simplePaginate($this->perPage);
        }
        return $model->simplePaginate($this->perPage);
    }


    /**
     * Return a collection of members for public display
     * @param bool $showPrivateMembers Some members don't want to listed on public pages, set to true to show everyone
     * @return mixed
     */
    public function getActivePublicList($showPrivateMembers = false)
    {
        if ($showPrivateMembers) {
            return $this->model->with('profile', 'roles')->active()->where('status', '!=', 'leaving')->orderBy('given_name')->get();
        } else {
            return $this->model->with('profile', 'roles')->active()->where('status', '!=', 'leaving')->where('profile_private', 0)->orderBy('given_name')->get();
        }
    }

    public function getTrustedMissingPhotos()
    {
        return \DB::table('users')->join('profile_data', 'users.id', '=', 'profile_data.user_id')->where('key_holder', '1')->where('active', '1')->where('profile_data.profile_photo', 0)->get();
    }

    /**
     * Get a list of active members suitable for use in a dropdown
     * @return array
     */
    public function getAllAsDropdown()
    {
        $members = $this->getActive();
        $memberDropdown = [];
        foreach ($members as $member) {
            $memberDropdown[$member->id] = $member->name;
        }
        return $memberDropdown;
    }

    /**
     * @param array   $memberData The new members details
     * @param boolean $isAdminCreating Is the user making the change an admin
     * @return User
     */
    public function registerMember(array $memberData, $isAdminCreating)
    {
        if (empty($memberData['profile_photo_private'])) {
            $memberData['profile_photo_private'] = false;
        }

        if (empty($memberData['password'])) {
            unset($memberData['password']);
        }

        $memberData['hash'] = str_random(30);

        $memberData['rules_agreed'] = $memberData['rules_agreed']? Carbon::now(): null;

        $user = $this->model->create($memberData);
        $this->profileDataRepository->createProfile($user->id);

        $this->addressRepository->saveUserAddress($user->id, $memberData['address'], $isAdminCreating);

        return $user;
    }

    /**
     * The user has setup a payment method of some kind so they are now considered active
     * This will kick off the automated member checking processes
     *
     * @param integer $userId
     */
    public function ensureMembershipActive($userId)
    {
        /** @var User $user */
        $user = $this->getById($userId);

        //user needs to have a recent sub charge and one that was paid or is due

        $user->active = true;
        $user->status = 'active';
        $user->save();

        $outstandingCharges = $this->subscriptionChargeRepository->hasOutstandingCharges($userId);

        //If the user doesn't have any charges currently processing or they dont have an expiry date or are past their expiry data create a charge
        if ( ! $outstandingCharges && ( ! $user->subscription_expires || $user->subscription_expires->lt(Carbon::now()))) {
            //create a charge

            $chargeDate = Carbon::now();

            //If we are passed the end of month cutoff push the charge date forward to their actual charge date
            if ((Carbon::now()->day > 28) && $user->payment_day === 1) {
                $chargeDate = $chargeDate->day(1)->addMonth();
            }

            if ($user->payment_method == 'gocardless-variable') {
                $this->subscriptionChargeRepository->createChargeAndBillDD($userId, $chargeDate, $user->monthly_subscription, 'due', $user->mandate_id);
            } else {
                // This will create the monthly sub charge but not take any money, that will happen tomorrow during the normal run
                $this->subscriptionChargeRepository->createCharge($userId, $chargeDate, $user->monthly_subscription, 'due');
            }
        }
    }

    /**
     * @param integer $userId           The ID of the user to be updated
     * @param array   $recordData       The data to be updated
     * @param boolean $isAdminUpdating  Is the user making the change an admin
     */
    public function updateMember($userId, array $recordData, $isAdminUpdating)
    {
        //If the password field hasn't been filled in unset it so it doesn't get set to a blank password
        if (empty($recordData['password'])) {
            unset($recordData['password']);
        }

        //Update the main user record
        $this->update($userId, $recordData);

        //Update the user address
        if (isset($recordData['address']) && is_array($recordData['address'])) {
            $this->addressRepository->updateUserAddress($userId, $recordData['address'], $isAdminUpdating);
        }
    }

    /**
     * The member has left, disable their account and cancel any out stand sub charge records
     * The payment day is also cleared so when they start again the payment is charge happens at restart time
     *
     * @param $userId
     */
    public function memberLeft($userId)
    {
        $user = $this->getById($userId);
        $user->active       = false;
        $user->status       = 'left';
        $user->payment_day  = '';
        $user->save();

        $this->subscriptionChargeRepository->cancelOutstandingCharges($userId);
    }

    public function recordGoCardlessMandateDetails($userId, $subscriptionId)
    {
        /** @var User $user */
        $user = $this->getById($userId);
        $user->mandate_id          = $subscriptionId;
        $user->gocardless_setup_id = null;
        $user->save();
    }

    public function updateUserPaymentMethod($userId, $paymentMethod, $paymentDay = null)
    {
        /** @var User $user */
        $user = $this->getById($userId);
        $user->payment_method = $paymentMethod;
        if ($paymentDay) {
            $user->payment_day = $paymentDay;
        }
        $user->save();
    }

    public function recordGoCardlessSubscription($userId, $subscriptionId, $paymentDay = null)
    {
        /** @var User $user */
        $user = $this->getById($userId);
        if ($paymentDay) {
            $user->payment_day = $paymentDay;
        }
        $user->subscription_id = $subscriptionId;
        $user->save();
    }

    /**
     * Record the fact that the user has agreed to the member induction and the rules
     *
     * @param $userId
     */
    public function recordInductionCompleted($userId)
    {
        $user = $this->getById($userId);

        $user->induction_completed = true;

        $user->rules_agreed = $user->rules_agreed? $user->rules_agreed: Carbon::now();

        $user->save();
    }

    public function getPendingInductionConfirmation()
    {
        return $this->model
            ->where('status', '!=', 'left')
            ->where('induction_completed', true)
            ->where('inducted_by', null)
            ->get();
    }

}
