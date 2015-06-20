<?php namespace BB\Http\Middleware;

use Closure;

class SSLOnly
{
    /**
     * Verify the incoming request is via an ssl connection unless its on an approved url
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( ! $request->isSecure() && env('FORCE_SECURE', 'true')) {
            if ((strpos($request->path(), 'access-control/') !== 0) && ($request->path() !== 'acs') && ($request->path() !== 'acs/spark')) {
                return redirect()->secure($request->path());
            }
        }

        return $next($request);
    }
}
