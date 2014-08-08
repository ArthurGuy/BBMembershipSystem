<?php

class ReminderController extends \BaseController {

    protected $layout = 'layouts.main';

    function __construct()
    {
        View::share('body_class', 'register_login');
    }

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function create()
	{
        $this->layout->content = View::make('password.create');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function store()
	{
        $response = Password::remind(Input::only('email'), function($message)
        {
            $message->subject('Reset your password');
        });
		switch ($response)
		{
			case Password::INVALID_USER:
				return Redirect::back()->withErrors(Lang::get($response));

			case Password::REMINDER_SENT:
				return Redirect::back()->withSuccess(Lang::get($response));
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

        $this->layout->content = View::make('password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

        //We aren't using a confirm password box so this can be faked
        $credentials['password_confirmation'] = $credentials['password'];

        Password::validator(function($credentials)
        {
            return strlen($credentials['password']) >= 8;
        });

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = $password;

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->withErrors(Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('/login')->withSuccess("Your password has been changed");
		}
	}

}
