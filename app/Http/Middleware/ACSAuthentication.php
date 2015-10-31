<?php

namespace BB\Http\Middleware;

use BB\Exceptions\AuthenticationException;
use Closure;

class ACSAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('ApiKey');
        if ($apiKey != 'my-token') {
            throw new AuthenticationException();
        }
        return $next($request);
    }
}
