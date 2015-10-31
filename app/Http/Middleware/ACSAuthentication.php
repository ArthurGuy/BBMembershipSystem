<?php

namespace BB\Http\Middleware;

use BB\Exceptions\AuthenticationException;
use BB\Repo\ACSNodeRepository;
use Closure;

class ACSAuthentication
{
    /**
     * @var ACSNodeRepository
     */
    private $ACSNodeRepository;

    /**
     * ACSAuthentication constructor.
     *
     * @param ACSNodeRepository $ACSNodeRepository
     */
    public function __construct(ACSNodeRepository $ACSNodeRepository)
    {
        $this->ACSNodeRepository = $ACSNodeRepository;
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
        try {
            $node = $this->ACSNodeRepository->findByAPIKey($request->header('ApiKey'));
        } catch (\Exception $e) {
            throw new AuthenticationException("Key not recognised");
        }

        //Possibly do some checking here on the access rights for the acs node

        return $next($request);
    }
}
