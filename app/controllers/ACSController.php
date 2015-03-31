<?php

use BB\Repo\DeviceRepository;
use BB\Validators\ACSValidator;

class ACSController extends Controller
{

    /**
     * @var DeviceRepository
     */
    private $deviceRepository;
    /**
     * @var ACSValidator
     */
    private $ACSValidator;
    /**
     * @var \BB\Services\KeyFobAccess
     */
    private $keyFobAccess;

    function __construct(DeviceRepository $deviceRepository, ACSValidator $ACSValidator, \BB\Services\KeyFobAccess $keyFobAccess)
    {
        $this->deviceRepository = $deviceRepository;
        $this->ACSValidator     = $ACSValidator;
        $this->keyFobAccess     = $keyFobAccess;
    }

    public function get()
    {

    }

    public function update()
    {
        $data = Request::only('device', 'key_fob', 'message', 'type', 'time');

        $this->ACSValidator->validate($data);

        Log::debug(json_encode($data));


        //System messages
        if (in_array($data['message'], ['boot', 'heartbeat'])) {
            return $this->handleSystemCheckIn($data['message'], $data['device']);
        }



        switch($data['type']) {
            case 'door':
                return $this->handleDoor($data);
            case 'equipment':
                return $this->handleDevice($data);
        }
    }

    private function handleDoor($data)
    {
        $error = false;

        //Door entry is quite simple - this will just deal with lookups

        try {
            $this->keyFobAccess->verifyForEntry($data['key_fob'], 'main-door', $data['time']);

            $this->keyFobAccess->logSuccess();
        } catch(\Exception $e) {
            $error = true;
        }

        $cmd = '';
        $cmd = 'refresh';

        if (!$error) {
            $responseData = ['member' => $this->keyFobAccess->getMemberName(), 'valid' => '1', 'cmd' => $cmd];
        } else {
            $responseData = ['valid' => '0'];
        }
        return $this->sendResponse($responseData);
    }

    private function handleDevice($data)
    {
        $device = $this->deviceRepository->getByName($data['device']);

        if ($data['message'] == 'boot') {
            $this->deviceRepository->logBoot($data['device']);
        } elseif ($data['message'] == 'heartbeat') {
            $this->deviceRepository->logHeartbeat($data['device']);
        }

        //$member = $this->keyFobAccess->verifyForDevice($data['key_fob'], 'laser');

        $deviceStatus = 'ok';

        $responseData = ['deviceStatus'=>$deviceStatus];
        return $this->sendResponse($responseData);
    }


    /**
     * System checkins are common across all devices
     * Record the time and return pending status messages
     * @param $message
     * @param $device
     * @return Response
     */
    private function handleSystemCheckIn($message, $device)
    {
        switch ($message) {
            case 'boot':
                $this->deviceRepository->logBoot($device);
                break;
            case 'heartbeat':
                $this->deviceRepository->logHeartbeat($device);
                break;
        }

        //The command comes from the database and will instruct the door entry system to clear its memory if set
        $cmd = '';
        $cmd = 'refresh';

        //The status of the device attached to th acs - used to lock down/disable equipment
        $deviceStatus = '1';

        $responseData = ['cmd'=>$cmd, 'deviceStatus'=>$deviceStatus];
        return $this->sendResponse($responseData);
    }

    /**
     * Json encode the response data and return
     * @param array $responseData
     * @return \Response
     */
    private function sendResponse(array $responseData)
    {
        $responseData['time'] = time();
        $response = \Response::json($responseData);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }

} 