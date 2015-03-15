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
        $data = Request::only('device', 'key_fob', 'message');

        $this->ACSValidator->validate($data);

        Log::debug(json_encode($data));

        $device = $this->deviceRepository->getByName($data['device']);

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

        $member = null;
        if ($keyFob) {
            $member = 'valid';
        }

        $deviceStatus = 'ok';

        $responseData = ['device'=>$data['device'], 'time'=>time(), 'deviceStatus'=>$deviceStatus, 'member'=>$member];

        $response = Response::json($responseData);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }

} 