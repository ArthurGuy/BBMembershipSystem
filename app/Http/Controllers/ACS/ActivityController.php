<?php

namespace BB\Http\Controllers\ACS;

use BB\Events\MemberActivity;
use BB\Repo\ACSNodeRepository;
use BB\Repo\EquipmentLogRepository;
use BB\Services\KeyFobAccess;
use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;
use Swagger\Annotations as SWG;

class ActivityController extends Controller
{
    /**
     * @var ACSNodeRepository
     */
    private $ACSNodeRepository;
    /**
     * @var EquipmentLogRepository
     */
    private $equipmentLogRepository;
    /**
     * @var KeyFobAccess
     */
    private $fobAccess;

    /**
     * NodeController constructor.
     *
     * @param ACSNodeRepository $ACSNodeRepository
     */
    public function __construct(ACSNodeRepository $ACSNodeRepository, EquipmentLogRepository $equipmentLogRepository, KeyFobAccess $fobAccess)
    {
        $this->ACSNodeRepository = $ACSNodeRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->fobAccess = $fobAccess;
    }

    /**
     * Record the start of a new session
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Post(
     *     path="/acs/activity",
     *     tags={"activity"},
     *     description="Record the start of a period of activity, e.g. someone signing into the laser cutter",
     *     @SWG\Parameter(name="activity", in="body", required=true, @SWG\Schema(ref="#/definitions/Activity")),
     *     @SWG\Response(response="201", description="Activity started"),
     *     @SWG\Response(response="404", description="Key fob not found"),
     *     security={{"api_key": {}}}
     * )
     */
    public function store(Request $request)
    {
        $activityRequest = new Requests\ACS\Activity($request);

        $keyFob = $this->fobAccess->extendedKeyFobLookup($activityRequest->getTagId());

        $activityId = $this->equipmentLogRepository->recordStartCloseExisting($keyFob->user->id, $keyFob->id, $activityRequest->getDevice());

        event(new MemberActivity($keyFob, $activityRequest->getDevice()));

        return ['activityId' => $activityId];
    }

    /**
     * Update an ongoing activity
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Put(
     *     path="/acs/activity/{activityId}",
     *     tags={"activity"},
     *     description="Record a heartbeat message for a period of activity, used to ensure activity periods are correctly recorded",
     *     @SWG\Parameter(name="activityId", in="path", type="string", required=true),
     *     @SWG\Response(response="200", description="Activity Heartbeat recorded"),
     *     security={{"api_key": {}}}
     * )
     */
    public function update(Request $request, $activityId)
    {
        $keyFob = $this->fobAccess->extendedKeyFobLookup($request->get('tagId'));

        $this->equipmentLogRepository->recordActivity($activityId);

        return $activityId;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Delete(
     *     path="/acs/activity/{activityId}",
     *     tags={"activity"},
     *     description="End a period of an activity",
     *     @SWG\Parameter(name="activityId", in="path", type="string", required=true),
     *     @SWG\Response(response="204", description="Activity ended/deleted"),
     *     @SWG\Response(response="400", description="Session invalid"),
     *     security={{"api_key": {}}}
     * )
     */
    public function destroy(Request $request, $activityId)
    {
        $keyFob = $this->fobAccess->extendedKeyFobLookup($request->get('tagId'));

        $this->equipmentLogRepository->endSession($activityId);

        return $activityId;
    }

}
