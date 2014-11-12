<?php


class AccountController extends \BaseController {

    protected $layout = 'layouts.main';

    protected $userForm;

    /**
     * @var BB\Helpers\UserImage
     */
    private $userImage;
    /**
     * @var BB\Validators\UserDetails
     */
    private $userDetailsForm;
    /**
     * @var \BB\Repo\ProfileDataRepository
     */
    private $profileRepo;
    /**
     * @var \BB\Repo\InductionRepository
     */
    private $inductionRepository;
    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Validators\ProfileValidator
     */
    private $profileValidator;


    function __construct(
        \BB\Validators\UserValidator $userForm,
        \BB\Validators\UpdateSubscription $updateSubscriptionAdminForm,
        \BB\Helpers\GoCardlessHelper $goCardless,
        \BB\Helpers\UserImage $userImage,
        \BB\Validators\UserDetails $userDetailsForm,
        \BB\Repo\ProfileDataRepository $profileRepo,
        \BB\Repo\InductionRepository $inductionRepository,
        \BB\Repo\EquipmentRepository $equipmentRepository,
        \BB\Repo\UserRepository $userRepository,
        \BB\Validators\ProfileValidator $profileValidator)
    {
        $this->userForm = $userForm;
        $this->updateSubscriptionAdminForm = $updateSubscriptionAdminForm;
        $this->goCardless = $goCardless;
        $this->userImage = $userImage;
        $this->userDetailsForm = $userDetailsForm;
        $this->profileRepo = $profileRepo;
        $this->inductionRepository = $inductionRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->userRepository = $userRepository;
        $this->profileValidator = $profileValidator;

        //This tones down some validation rules for admins
        $this->userForm->setAdminOverride(!Auth::guest() && Auth::user()->isAdmin());

        $this->beforeFilter('role:member', array('except' => ['create', 'store']));
        $this->beforeFilter('role:admin', array('only' => ['index']));
        //$this->beforeFilter('guest', array('only' => ['create', 'store']));

        $paymentMethods = [
            'gocardless'    => 'GoCardless',
            'paypal'        => 'PayPal',
            'bank-transfer' => 'Manual Bank Transfer',
            'other'         => 'Other'
        ];
        View::share('paymentMethods', $paymentMethods);
        View::share('paymentDays', array_combine(range(1, 31), range(1, 31)));

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $sortBy = Request::get('sortBy');
        $direction = Request::get('direction', 'asc');
        $showLeft = \Request::get('showLeft', 0);
        $users = $this->userRepository->getPaginated(compact('sortBy', 'direction', 'showLeft'));
        return View::make('account.index')->withUsers($users);
	}


    public function trustedMissingPhotos()
    {
        $users = $this->userRepository->getTrustedMissingPhotos();
        return $users;
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        View::share('body_class', 'register_login');
        return View::make('account.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('given_name', 'family_name', 'email', 'secondary_email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'monthly_subscription', 'emergency_contact', 'new_profile_photo', 'profile_photo_private');

        $this->userForm->validate($input);
        $this->profileValidator->validate($input);

        if (empty($input['profile_photo_private']))
            $input['profile_photo_private'] = false;

        if (empty($input['password']))
            unset($input['password']);

        $user = User::create($input);
        $this->profileRepo->createProfile($user->id);


        if (Input::file('new_profile_photo'))
        {
            try
            {
                $this->userImage->uploadPhoto($user->hash, Input::file('new_profile_photo')->getRealPath(), true);

                $this->profileRepo->update($user->id, ['new_profile_photo'=>1, 'profile_photo_private'=>$input['profile_photo_private']]);
            }
            catch (\Exception $e)
            {
                Log::error($e);
            }
        }

        //If this isn't an admin user creating the record log them in
        if (Auth::guest() || !Auth::user()->isAdmin())
        {
            Auth::login($user);
        }

        return Redirect::route('account.show', $user->id);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = User::findWithPermission($id);

        $inductions = $this->equipmentRepository->allPaid();

        $userInductions = $user->inductions()->get();
        foreach ($inductions as $key => $induction)
        {
            $inductions[$key]->userInduction = false;
            foreach ($userInductions as $userInduction)
            {
                if ($userInduction->key == $key)
                {
                    $inductions[$key]->userInduction = $userInduction;
                }
            }
        }

        return View::make('account.show')->withUser($user)->withInductions($inductions);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $user = User::findWithPermission($id);

        return View::make('account.edit')->withUser($user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $user = User::findWithPermission($id);
        $input = Input::only('given_name', 'family_name', 'email', 'secondary_email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'emergency_contact', 'profile_private');

        $this->userForm->validate($input, $user->id);

        if (empty($input['password']))
        {
            unset($input['password']);
        }
        $user->update($input);

        /*
        if (Input::file('profile_photo'))
        {
            try
            {
                $this->userImage->uploadPhoto($user->hash, Input::file('profile_photo')->getRealPath());

                $user->profilePhoto(true);
            }
            catch (\Exception $e)
            {
                Log::error($e);
            }
        }
        */

        Notification::success("Details Updated");
        return Redirect::route('account.show', $user->id);
	}



    public function adminUpdate($id)
    {
        $user = User::findWithPermission($id);
        $input = Input::only('trusted', 'key_holder', 'induction_completed', 'profile_photo_checked');

        //$this->userDetailsForm->validate($input, $user->id);

        if (Input::has('trusted'))
            $user->trusted = Input::get('trusted');

        if (Input::has('key_holder'))
            $user->key_holder = Input::get('key_holder');

        if (Input::has('induction_completed'))
            $user->induction_completed = Input::get('induction_completed');

        if (Input::has('profile_photo_checked') && Input::get('profile_photo_checked')) {
            $profile = $user->profile()->first();

            $this->userImage->approveNewImage($user->hash);

            $profile->update(['new_profile_photo'=>false, 'profile_photo'=>true]);
        }

        $user->save();
        Notification::success("Details Updated");
        return Redirect::route('account.show', $user->id);
    }


    public function alterSubscription($id)
    {
        $user = User::findWithPermission($id);
        $input = Input::all();

        $this->updateSubscriptionAdminForm->validate($input, $user->id);

        if (($user->payment_method == 'gocardless') && ($input['payment_method'] != 'gocardless'))
        {
            //Changing away from GoCardless
            $subscription = $this->goCardless->cancelSubscription($user->subscription_id);
            if ($subscription->status == 'cancelled')
            {
                $user->cancelSubscription();
            }
        }

        $user->updateSubscription($input['payment_method'], $input['payment_day']);

        Notification::success("Details Updated");
        return Redirect::route('account.show', $user->id);
    }

    public function confirmEmail($id, $hash)
    {
        $user = User::find($id);
        if ($user && $user->hash == $hash)
        {
            $user->emailConfirmed();
            Notification::success("Email address confirmed, thank you");
            return Redirect::route('account.show', $user->id);
        }
        Notification::error("Error confirming email address");
        return Redirect::route('home');
    }



	public function destroy($id)
	{
        $user = User::findWithPermission($id);

        //No one will ever leaves the system but we can at least update their status to left.
        $user->setLeaving();

        Notification::success("Updated status to leaving");
        return Redirect::route('account.show', $user->id);
	}


    public function rejoin($id)
    {
        $user = User::findWithPermission($id);
        $user->rejoin();
        Notification::success("Details Updated");
        return Redirect::route('account.show', $user->id);
    }

    public function updateSubscriptionAmount($id)
    {
        $user = User::findWithPermission($id);
        $user->updateSubAmount(Input::get('monthly_subscription'));
        Notification::success("Details Updated");
        return Redirect::route('account.show', $user->id);
    }
}
