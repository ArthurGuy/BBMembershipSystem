<?php

namespace BB\Http\Middleware;

use BB\Entities\ACSNode;
use BB\Exceptions\AuthenticationException;
use Closure;

class ACSAuthentication
{
    /**
     * @var ACSNode
     */
    private $ACSNode;

    /**
     * ACSAuthentication constructor.
     *
     * @param ACSNode $ACSNode
     */
    public function __construct(ACSNode $ACSNode)
    {
        $this->ACSNode = $ACSNode;
    }

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

        try {
            $node = $this->ACSNode->findByAPIKey($apiKey);
        } catch (\Exception $e) {
            throw new AuthenticationException("Key not recognised");
        }

        //Possibly do some checking here on the access rights for the acs node

        return $next($request);
    }
}
