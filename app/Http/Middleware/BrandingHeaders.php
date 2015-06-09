<?php namespace BB\Http\Middleware;

use Closure;

class BrandingHeaders {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
        //Don't add the header to acs requests
        if ((strpos($request->path(), 'access-control/') !== 0) && ($request->path() !== 'acs') && ($request->path() !== 'acs/spark')) {
            return $next($request);
        }

		return $next($request)->header('Built-By', 'arthurguy.co.uk');
	}

}
