<?php

use Illuminate\Support\Facades\Response;

class AccessControlController extends Controller
{

    /**
     * @var \BB\Services\BuildingAccess
     */
    private $buildingAccess;
    /**
     * @var \BB\Repo\DeviceRepository
     */
    private $deviceRepository;

    function __construct(\BB\Services\BuildingAccess $buildingAccess, \BB\Repo\DeviceRepository $deviceRepository)
    {
        $this->buildingAccess = $buildingAccess;
        $this->deviceRepository = $deviceRepository;
    }

    public function mainDoor()
    {
        $message = null;

        $receivedData = trim(Input::get('data'));

        //Log::debug("New System. Entry message received: ".$receivedData);

        //What access point is this?
        $this->buildingAccess->setDeviceKey('main-door');


        try {

            //Decode the message and store the message parameters in the building access object
            $this->buildingAccess->decodeDeviceCommand($receivedData);

            //Verify everything is good
            $this->buildingAccess->validateData();

        } catch (\BB\Exceptions\ValidationException $e) {

            Log::debug("Entry message received - failed: ".$receivedData);

            //The data was invalid or the user doesnt have access
            $response = Response::make(json_encode(['valid' => '0', 'msg' => $e->getMessage()]).PHP_EOL, 200);
            $response->headers->set('Content-Length', strlen($response->getContent()));
            return $response;
        }


        //Is this a system message
        if ($this->buildingAccess->isSystemMessage()) {
            return $this->handleSystemMessage($receivedData);
        }


        $this->buildingAccess->logSuccess();

        $userName = substr($this->buildingAccess->getUser()->given_name, 0, 20);
        $responseBody = json_encode(['valid' => '1', 'msg' => $userName]);

        $response = Response::make($responseBody.PHP_EOL, 200);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }


    public function status()
    {
        $keyId = Input::get('data');
        try {
            $keyFob = $this->lookupKeyFob($keyId);
        } catch (Exception $e) {

            return Response::make(json_encode(['valid'=>'0']), 200);
        }
        $user = $keyFob->user()->first();

        $log = new AccessLog();
        $log->key_fob_id = $keyFob->id;
        $log->user_id = $user->id;
        $log->service = 'status';
        $log->save();
        $statusString = $user->status;
        return Response::make(json_encode(['valid'=>'1', 'name'=>$user->name, 'status'=>$statusString]), 200);
    }

    /*
    public function legacy()
    {
        $keyId = Input::get('data');
        $keyParts = explode(':', $keyId);


        //Verify the key fob code has been extracted
        if (!is_array($keyParts) || count($keyParts) != 3)
        {
            return Response::make("NOTFOUND", 200);
        }
        $keyId = $keyParts[0];


        //Lookup the fob id and the user
        try {
            $keyFob = $this->lookupKeyFob($keyId);
        } catch (Exception $e) {
            Log::debug("Keyfob code not found ".$keyId);
            return Response::make("NOTFOUND", 200);
        }
        $user = $keyFob->user()->first();


        //Log this request
        $log = new AccessLog();
        $log->key_fob_id = $keyFob->id;
        $log->user_id = $user->id;
        $log->service = 'main-door';


        //Return a status based on the users status
        if ($user->keyholderStatus()) {
            $log->response = 200;
            $log->save();
            return Response::make("OK:8F00:".$user->name, 200);
        } elseif ($user->active) {
            $log->response = 403;
            $log->save();
            return Response::make("NOTFOUND", 200);
        } else {
            $log->response = 402;
            $log->save();
            return Response::make("NOTFOUND", 200);
        }
    }
    */

    private function lookupKeyFob($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        } catch (Exception $e) {
            $keyId = substr('BB'.$keyId, 0, 12);
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        }
    }

    /**
     * @param $receivedData
     * @return mixed
     */
    public function handleSystemMessage($receivedData)
    {
        $receivedData = substr($receivedData, 1, strlen($receivedData));

        $messageParts = ["", ""];
        if (strpos($receivedData, '|') !== false) {
            $messageParts = explode('|', $receivedData);
        }

        $device  = $messageParts[0];
        $message = $messageParts[1];

        if ($message == 'boot') {
            $this->deviceRepository->logBoot($device);
        } elseif ($message == 'heartbeat') {
            $this->deviceRepository->logHeartbeat($device);
        }

        Log::debug("System Message: ".$receivedData);

        return Response::make(PHP_EOL, 200);
    }

} 