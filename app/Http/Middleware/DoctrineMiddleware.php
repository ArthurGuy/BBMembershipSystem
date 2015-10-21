<?php

namespace BB\Http\Middleware;

use Closure;
use Doctrine\ORM\EntityManager;

class DoctrineMiddleware
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DoctrineMiddleware constructor.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->entityManager->flush();
        
        return $response;
    }
}
