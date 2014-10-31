<?php

class SessionController extends \BaseController {

    protected $loginForm;

    function __construct(\BB\Validators\Login $loginForm)
    {
        $this->loginForm = $loginForm;
        View::share('body_class', 'register_login');
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        if (!Auth::guest()) {
            return Redirect::to('account/'.Auth::id());
        }
        return View::make('session.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('email', 'password');

        $this->loginForm->validate($input);

        if (Auth::attempt($input, true))
        {
            return Redirect::intended('account/'.Auth::id());
        }

        Notification::error("Invalid login details");
        return Redirect::back()->withInput();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id=null)
	{
        Auth::logout();

        Notification::success('Logged Out');

        return Redirect::home();
	}


}
