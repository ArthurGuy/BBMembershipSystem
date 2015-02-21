<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));



Event::subscribe('BB\Handlers\PaymentEventHandler');

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});


App::error(function(\BB\Exceptions\AuthenticationException $exception)
{
    $userString = null;
    if (Auth::guest()) {
        $userString = "A guest";
    } else {
        $userString = Auth::user()->name;
    }
    Log::warning($userString." tried to access something they weren't supposed to.");

    return Response::view('errors.403', [], 403);
});


App::error(function(NotFoundHttpException $exception)
{
    return Response::view('errors.404', [], 404);
});
App::error(function(ModelNotFoundException $exception)
{
    return Response::view('errors.404', [], 404);
});


/**
 * Catch validation errors and return them back to the previous page/form
 */
App::error(function(\BB\Exceptions\FormValidationException $exception)
{
    if (Request::wantsJson()) {
        return Response::json($exception->getErrors(), 400);
    } else {
        Notification::error("Something wasn't right, please check the form for errors", $exception->getErrors());
        return Redirect::back()->withInput();
    }
});

App::error(function(\BB\Exceptions\ValidationException $exception)
{
    if (Request::wantsJson()) {
        return Response::json($exception->getMessage(), 400);
    } else {
        Notification::error($exception->getMessage());
        return Redirect::back()->withInput();
    }
});

App::error(function(\BB\Exceptions\NotImplementedException $exception)
{
    Notification::error("NotImplementedException: ".$exception->getMessage());
    Log::warning($exception);
    return Redirect::back()->withInput();
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
