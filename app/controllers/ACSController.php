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

    function __construct(DeviceRepository $deviceRepository, ACSValidator $ACSValidator)
    {
        $this->deviceRepository = $deviceRepository;
        $this->ACSValidator     = $ACSValidator;
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
        $keyFob = null;
        $memberName = null;
        $valid = '0';
        $error = false;

        //Door entry is quite simple - this will just deal with lookups

        try {
            $keyFob = KeyFob::lookup($data['key_fob']);
        } catch(\Exception $e) {
            $error = true;
        }

        if ($keyFob) {
            $member = $keyFob->user()->first();
            $memberName = $member->given_name;
            $valid = '1';

            //@TODO: Verify member has access
            //@TODO: Record access log
        }

        $cmd = '';
        $cmd = 'refresh';

        if (!$error) {
            $responseData = ['member' => $memberName, 'valid' => $valid, 'cmd' => $cmd];
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