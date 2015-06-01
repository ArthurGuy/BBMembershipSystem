<?php namespace BB\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;

class ACSSessionControl
{

    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new filter instance.
     *
     * @param Application|\Illuminate\Contracts\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Disable sessions for requests to the acs endpoint
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ((strpos($request->path(), 'access-control/') === 0) || ($request->path() === 'acs')) {
            $this->app['config']->set('session.driver', 'array');
        }


        return $next($request);
    }
}
