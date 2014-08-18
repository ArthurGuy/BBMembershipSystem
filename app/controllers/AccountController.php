<?php


class AccountController extends \BaseController {

    protected $layout = 'layouts.main';

    protected $userForm;

    /**
     * @var BB\Helpers\UserImage
     */
    private $userImage;


    function __construct(\BB\Forms\User $userForm, \BB\Forms\UpdateSubscription $updateSubscriptionAdminForm, \BB\Helpers\GoCardlessHelper $goCardless, \BB\Helpers\UserImage $userImage)
    {
        $this->userForm = $userForm;
        $this->updateSubscriptionAdminForm = $updateSubscriptionAdminForm;
        $this->goCardless = $goCardless;
        $this->userImage = $userImage;

        //This tones down some validation rules for admins
        $this->userForm->setAdminOverride(!Auth::guest() && Auth::user()->isAdmin());

        $this->beforeFilter('auth', array('except' => ['create', 'store']));
        $this->beforeFilter('auth.admin', array('only' => ['index']));
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
        $users = User::orderBy('active', true)->paginate(100);
        $numActiveUsers = User::active()->count();
        $this->layout->content = View::make('account.index')->withUsers($users)->with('numActiveUsers', $numActiveUsers);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        View::share('body_class', 'register_login');
        $this->layout->content = View::make('account.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('given_name', 'family_name', 'email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'monthly_subscription', 'emergency_contact', 'profile_photo', 'profile_photo_private');

		try
        {
            $this->userForm->validate($input);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        if (empty($input['profile_photo_private']))
            $input['profile_photo_private'] = false;

        if (empty($input['password']))
            unset($input['password']);

        $user = User::create($input);

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

        $inductions = Induction::inductionList();

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

        $this->layout->content = View::make('account.show')->withUser($user)->withInductions($inductions);
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

        $this->layout->content = View::make('account.edit')->withUser($user);
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
        $input = Input::only('given_name', 'family_name', 'email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'monthly_subscription', 'emergency_contact', 'profile_photo', 'profile_photo_private');

        try
        {
            $this->userForm->validate($input, $user->id);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        if (empty($input['password']))
        {
            unset($input['password']);
        }
        $user->update($input);

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

        return Redirect::route('account.show', $user->id)->withSuccess("Details Updated");
	}


    public function alterSubscription($id)
    {
        $user = User::findWithPermission($id);
        $input = Input::all();

        try
        {
            $this->updateSubscriptionAdminForm->validate($input, $user->id);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

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

        return Redirect::route('account.show', $user->id)->withSuccess("Details Updated");
    }

    public function confirmEmail($id, $hash)
    {
        $user = User::find($id);
        if ($user && $user->hash == $hash)
        {
            $user->emailConfirmed();
            return Redirect::route('account.show', $user->id)->withSuccess("Email address confirmed");
        }
        return Redirect::route('home')->withErrors("Error confirming email address");
    }



	public function destroy($id)
	{
        $user = User::findWithPermission($id);

        //No one will ever leaves the system but we can at least update their status to left.
        $user->leave();

        if ($user->id == Auth::user()->id)
        {
            Auth::logout();
            return Redirect::home()->withSuccess("We have marked you as having left Build Brighton.");
        }
        return Redirect::route('account.show', $user->id)->withSuccess("User marked as having left Build Brighton.");
	}

    public function sendWelcomeEmails()
    {
        $users = User::active()->notSpecialCase()->get();
        //$users = User::where('email', 'arthur@arthurguy.co.uk')->get();
        echo $users;
        exit;
        foreach ($users as $user)
        {
            \Mail::send('emails.new-system-intro', ['user'=>$user], function($message) use ($user)
            {
                $message->to($user->email, $user->name)->subject('Welcome to the new Build Brighton Member System');
            });
            set_time_limit(10);
        }
        exit;
    }

}
