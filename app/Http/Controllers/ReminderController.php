<?php namespace BB\Http\Controllers;

use BB\Exceptions\FormValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReminderController extends Controller
{

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function create()
	{

        return view('password.create');
	}

    /**
     * Handle a POST request to remind a user of their password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
	public function store(Request $request)
	{
        $validator = app('Illuminate\Contracts\Validation\Factory')->make($request->all(), ['email' => 'required|email']);
        if ($validator->fails()) {
            throw new FormValidationException('Error', $validator->errors());
        }

        $response = Password::sendResetLink($request->only('email'), function(Message $message)
        {
            $message->subject('Reset your password');
        });

        switch ($response) {
			case Password::INVALID_USER:
                \Notification::error(trans($response));
                return redirect()->back();

			case Password::RESET_LINK_SENT:
                \Notification::success(trans($response));
                return redirect()->back();
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
        if (is_null($token))
        {
            throw new NotFoundHttpException;
        }

        return view('password.reset')->with('token', $token);
	}

    /**
     * Handle a POST request to reset a user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|null
     */
	public function postReset(Request $request)
	{
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = app('Illuminate\Contracts\Validation\Factory')->make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            throw new FormValidationException('Error', $validator->errors());
        }

        //We aren't using a confirm password box so this can be faked
        $credentials['password_confirmation'] = $credentials['password'];

        $response = Password::reset($credentials, function($user, $password) {
            $user->password = $password;

            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                \Notification::success("Your password has been changed");
                return redirect()->home();

            default:
                \Notification::error(trans($response));
                return redirect()->back()->withInput();
        }
    }

}
