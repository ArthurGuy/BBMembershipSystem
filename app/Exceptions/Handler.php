<?php namespace BB\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
        \Log::error($e);
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
        if ($e instanceof FormValidationException) {
            if ($request->wantsJson()) {
                return \Response::json($e->getErrors(), 422);
            } else {
                \Notification::error("Something wasn't right, please check the form for errors", $e->getErrors());
                return redirect()->back()->withInput();
            }
        }

        if ($e instanceof ValidationException) {
            if ($request->wantsJson()) {
                return \Response::json($e->getMessage(), 422);
            } else {
                \Notification::error($e->getMessage());
                return redirect()->back()->withInput();
            }
        }

        if ($e instanceof NotImplementedException) {
            \Notification::error("NotImplementedException: ".$e->getMessage());
            \Log::warning($e);
            return redirect()->back()->withInput();
        }

        if ($e instanceof AuthenticationException) {
            if (\Auth::guest()) {
                $userString = "A guest";
            } else {
                $userString = \Auth::user()->name;
            }
            \Log::warning($userString." tried to access something they weren't supposed to.");

            return \Response::view('errors.403', [], 403);
        }

        if ($e instanceof NotFoundHttpException) {
            return \Response::view('errors.404', [], 404);
        }
        if ($e instanceof ModelNotFoundException) {
            return \Response::view('errors.404', [], 404);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return \Response::view('errors.404', [], 404);
        }


        if (config('app.debug'))
        {
            return $this->renderExceptionWithWhoops($e);
        }

		return parent::render($request, $e);
	}

    /**
     * Render an exception using Whoops.
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response($whoops->handleException($e), $e->getCode());
    }

}
