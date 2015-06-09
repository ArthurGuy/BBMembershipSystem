<?php namespace BB\Http\Controllers;

use BB\Repo\DeviceRepository;
use BB\Services\KeyFobAccess;
use BB\Validators\ACSValidator;

class ACSSparkController extends Controller
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
     * @var KeyFobAccess
     */
    private $keyFobAccess;

    function __construct(DeviceRepository $deviceRepository, ACSValidator $ACSValidator, KeyFobAccess $keyFobAccess)
    {
        $this->deviceRepository = $deviceRepository;
        $this->ACSValidator     = $ACSValidator;
        $this->keyFobAccess     = $keyFobAccess;
    }

    public function handle()
    {
        $data = \Request::only(['device', 'tag', 'service']);
        \Log::debug(json_encode($data));

        try {
            $keyFob = $this->keyFobAccess->lookupKeyFob($data['tag']);
        } catch (\Exception $e) {
            return \Response::make(false, 404);
        }
        $user = $keyFob->user()->first();
        
        return \Response::make('OK', 201);
    }
}