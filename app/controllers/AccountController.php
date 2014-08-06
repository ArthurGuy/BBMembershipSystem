<?php


class AccountController extends \BaseController {

    protected $layout = 'layouts.main';

    protected $registerForm;
    /**
     * @var BB\Forms\UpdateUser
     */
    private $updateUserForm;


    function __construct(\BB\Forms\Register $registerForm, \BB\Forms\UpdateUser $updateUserForm)
    {
        $this->registerForm = $registerForm;
        $this->updateUserForm = $updateUserForm;

        $this->beforeFilter('auth', array('except' => ['create', 'store']));
        $this->beforeFilter('auth.admin', array('only' => ['index']));
        $this->beforeFilter('guest', array('only' => ['create', 'store']));
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $users = User::all();
        $this->layout->content = View::make('account.index')->withUsers($users);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $this->layout->content = View::make('account.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('given_name', 'family_name', 'email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'monthly_subscription', 'emergency_contact');

		try
        {
            $this->registerForm->validate($input);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $user = User::create($input);

        Auth::login($user);

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
                    if ($userInduction->trained->year < 0)
                    {
                        $userInduction->trained = false;
                    }
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
        $input = Input::only('given_name', 'family_name', 'email', 'password', 'address_line_1', 'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'monthly_subscription', 'emergency_contact');

        try
        {
            $this->updateUserForm->validate($input, $user->id);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $user->update($input);

        //Auth::login($user);

        return Redirect::route('account.show', $user->id)->withSuccess("Details Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
