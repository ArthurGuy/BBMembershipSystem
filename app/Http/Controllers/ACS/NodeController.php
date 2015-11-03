<?php

namespace BB\Http\Controllers\ACS;

use BB\Repo\ACSNodeRepository;
use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;

class NodeController extends Controller
{
    /**
     * @var ACSNodeRepository
     */
    private $ACSNodeRepository;

    /**
     * NodeController constructor.
     *
     * @param ACSNodeRepository $ACSNodeRepository
     */
    public function __construct(ACSNodeRepository $ACSNodeRepository)
    {
        $this->ACSNodeRepository = $ACSNodeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/acs/node/boot",
     *     @SWG\Response(response="200", description="Boot recorded"),
     *     security={{"api_key": {}}}
     * )
     */
    public function boot(Request $request)
    {
        $node = $this->ACSNodeRepository->findByAPIKey($request->header('ApiKey'));
        $this->ACSNodeRepository->logBoot($node->device_id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/acs/node/heartbeat",
     *     @SWG\Response(response="200", description="Heartbeat recorded"),
     *     security={{"api_key": {}}}
     * )
     */
    public function heartbeat(Request $request)
    {
        $node = $this->ACSNodeRepository->findByAPIKey($request->header('ApiKey'));
        $this->ACSNodeRepository->logHeartbeat($node->device_id);
    }

}
