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

        Log::debug($data);


        //$device = $this->deviceRepository->getByName($data['device']);

        $response = Response::json($data);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }

} 