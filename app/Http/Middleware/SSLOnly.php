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
        if( ! $request->isSecure()) {
            if ((strpos($request->path(), 'access-control/') !== 0) && ($request->path() !== 'acs')) {
                return redirect()->secure($request->path());
            }
        }

        return $next($request);
    }
}
