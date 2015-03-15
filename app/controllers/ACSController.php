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
        $data = Request::only('device', 'key_fob', 'message', 'type');

        $this->ACSValidator->validate($data);

        Log::debug(json_encode($data));


        if ($data['type'] == 'door') {
            return $this->handleDoor($data);
        } elseif ($data['type'] == 'equipment') {
            return $this->handleDevice($data);
        }

    }

    private function handleDoor($data)
    {
        $keyFob = null;
        if ($data['message'] == 'boot') {
            $this->deviceRepository->logBoot($data['device']);
        } elseif ($data['message'] == 'heartbeat') {
            $this->deviceRepository->logHeartbeat($data['device']);
        } elseif ($data['message'] == 'lookup') {
            try {
                $keyFob = KeyFob::lookup($data['key_fob']);
            } catch(\Exception $e) {

            }
        }

        $member = '';
        $valid = '0';
        if ($keyFob) {
            $member = $keyFob->user_id;
            $valid = '1';
        }

        $cmd = '';
        $cmd = 'refresh';

        $responseData = ['time'=>time(), 'member'=>$member, 'valid'=>$valid, 'cmd'=>$cmd];

        $response = Response::json($responseData);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
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

        $responseData = ['time'=>time(), 'deviceStatus'=>$deviceStatus];

        $response = Response::json($responseData);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }

} 