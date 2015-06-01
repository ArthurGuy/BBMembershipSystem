<?php namespace BB\Http\Controllers;

use BB\Repo\ActivityRepository;

class DeviceAccessControlController extends Controller
{

    /**
     * @var ActivityRepository
     */
    protected $activityRepository;
    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var \BB\Repo\EquipmentLogRepository
     */
    private $equipmentLogRepository;
    /**
     * @var \BB\Services\DeviceSession
     */
    private $deviceSession;

    function __construct(
        \BB\Repo\ActivityRepository $activityRepository,
        \BB\Repo\EquipmentRepository $equipmentRepository,
        \BB\Repo\EquipmentLogRepository $equipmentLogRepository,
        \BB\Services\DeviceSession $deviceSession)
    {
        $this->activityRepository = $activityRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->deviceSession = $deviceSession;
    }

    public function device()
    {

        //process and validate incoming data

        $receivedData = \Input::get('data');
        //Log::debug($receivedData);

        //Decode and validate the received command
        try {
            $this->deviceSession->decodeDeviceCommand($receivedData);
            $this->deviceSession->validateData();
        } catch (\BB\Exceptions\ValidationException $e) {
            \Log::debug($e->getMessage());
            return \Response::make(json_encode(['valid'=>'0']), 200);
        }



        //Process start, ping, end command
        $this->deviceSession->processAction();


        return \Response::make(json_encode(['valid'=>'1', 'name'=>$this->deviceSession->getUser()->name]), 200);
    }

} 