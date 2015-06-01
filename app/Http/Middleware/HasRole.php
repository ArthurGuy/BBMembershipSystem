<?php namespace BB\Http\Middleware;

use BB\Exceptions\AuthenticationException;
use Closure;

class HasRole
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string                   $role
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $role = 'guest')
    {

        if (\Auth::guest()) {

            //Guests should be redirected to the login page as we make some links visible
            if (\Request::ajax()) {
                return \Response::make('Unauthorized', 401);
            } else {
                return \Redirect::guest('login');
            }

        } elseif (($role !== 'member') && ! \Auth::user()->hasRole($role)) {

            throw new AuthenticationException();

        } elseif (\Auth::user()->isBanned()) {

            throw new AuthenticationException();

        }

        return $next($request);
    }

}
