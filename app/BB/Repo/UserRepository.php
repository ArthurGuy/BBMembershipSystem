<?php namespace BB\Repo;

use Carbon\Carbon;

class UserRepository extends DBRepository {

    /**
     * @var \User
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

    function __construct(\User $model, AddressRepository $addressRepository, ProfileDataRepository $profileDataRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
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
    public function getActivePublicList($showPrivateMembers=false)
    {
        if ($showPrivateMembers) {
            return $this->model->with('profile')->active()->where('status', '!=', 'leaving')->orderBy('given_name')->get();
        } else {
            return $this->model->with('profile')->active()->where('status', '!=', 'leaving')->where('profile_private', 0)->orderBy('given_name')->get();
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
     * @param array   $memberData          The new members details
     * @param boolean $isAdminCreating     Is the user making the change an admin
     */
    public function registerMember(array $memberData, $isAdminCreating)
    {
        if (empty($memberData['profile_photo_private']))
            $memberData['profile_photo_private'] = false;

        if (empty($memberData['password']))
            unset($memberData['password']);

        $user = $this->model->create($memberData);
        $this->profileDataRepository->createProfile($user->id);

        $this->addressRepository->saveUserAddress($user->id, $memberData['address'], $isAdminCreating);

        return $user;
    }

    /**
     * The user has setup a payment method of some kind so they are now considered active
     * This will kick off the automated member checking processes
     * @param $userId
     */
    public function startMembership($userId)
    {
        $user = $this->getById($userId);
        $user->active = true;
        $user->status = 'active';
        $user->save();

        $this->subscriptionChargeRepository->createCharge($userId, Carbon::now(), $user->monthly_subscription);
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


}