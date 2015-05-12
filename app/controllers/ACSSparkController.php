<?php

use BB\Repo\DeviceRepository;
use BB\Services\KeyFobAccess;
use BB\Validators\ACSValidator;
use Illuminate\Support\Facades\Response;

class ACSSparkController extends Controller{

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
        $data = Request::only(['event', 'data', 'published_at', 'coreid']);
        Log::debug(json_encode($data));

        try {
            $keyFob = $this->keyFobAccess->lookupKeyFob($data['data']);
        } catch (Exception $e) {

            $client = new GuzzleHttp\Client();
            $client->post('https://api.spark.io/v1/devices/'.$data['coreid'].'/chk-resp', [
                'body' => [
                    'args' => json_encode(['name'=>'', 'status'=>'Unknown', 'balance'=>'', 'success'=>false]),
                    'access_token' => $_SERVER['SPARK_ACCESS_TOKEN']
                ]
            ]);

            return Response::make(json_encode(['valid'=>'0']), 404);
        }
        $user = $keyFob->user()->first();

        $client = new GuzzleHttp\Client();
        $client->post('https://api.spark.io/v1/devices/'.$data['coreid'].'/chk-resp', [
            'body' => [
                'args' => json_encode(['name'=>$user->name, 'status'=>$user->status, 'balance'=>number_format(($user->cash_balance/100), 2), 'success'=>true]),
                'access_token' => $_SERVER['SPARK_ACCESS_TOKEN']
            ]
        ]);

        return Response::make(json_encode(['name'=>$user->name]), 200);
    }
}