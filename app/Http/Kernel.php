<?php namespace BB\Http;

use BB\Http\Middleware\ACSAuthentication;
use BB\Http\Middleware\BrandingHeaders;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'BB\Http\Middleware\ACSSessionControl',
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        //'BB\Http\Middleware\VerifyCsrfToken',
        'BB\Http\Middleware\SSLOnly',
        BrandingHeaders::class,
        \Clockwork\Support\Laravel\ClockworkMiddleware::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'       => 'BB\Http\Middleware\Authenticate',
        'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'      => 'BB\Http\Middleware\RedirectIfAuthenticated',
        'role'       => 'BB\Http\Middleware\HasRole',
        'acs'        => ACSAuthentication::class,
    ];

}
