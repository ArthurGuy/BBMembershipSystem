<?php namespace BB\Http\Controllers;

use BB\Entities\DetectedDevice;
use BB\Repo\ACSNodeRepository;
use BB\Validators\ACSValidator;

class ACSController extends Controller
{

    /**
     * @var ACSNodeRepository
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

    function __construct(
        ACSNodeRepository $acsNodeRepository,
        ACSValidator $ACSValidator,
        \BB\Services\KeyFobAccess $keyFobAccess
    ) {
        $this->acsNodeRepository = $acsNodeRepository;
        $this->ACSValidator     = $ACSValidator;
        $this->keyFobAccess     = $keyFobAccess;
    }

    public function get()
    {

    }

    public function store()
    {
        $data = \Request::only('device', 'service', 'message', 'tag', 'time', 'payload', 'signature', 'nonce');

        //device = the device id from the devices table - unique to each piece of hardware
        //service = what the request is for, entry, usage, consumable
        //message = system message, heartbeat, boot
        //tag = the keyfob id
        //time = the time of the action
        //payload = any extra data relavent to the request
        //signature = an encoded value generated using a secret key - oauth style
        //nonce = a unique value suitable to stop replay attacks

        $this->ACSValidator->validate($data);





        //System messages
        if (in_array($data['message'], ['boot', 'heartbeat'])) {
            return $this->handleSystemCheckIn($data['message'], $data['device'], $data['service']);
        }


        switch ($data['service']) {
            case 'entry':
                return $this->handleDoor($data);
            case 'usage':
                return $this->handleDevice($data);
            case 'consumable':

                break;
            case 'shop':

                break;
            case 'status':
                return $this->returnMemberStatus($data);

                break;
            case 'device-scanner':
                $this->logDetectedDevices($data);
                break;
            default:
                \Log::debug(json_encode($data));
        }

        $responseArray = [
            'time'      => time(),
            'command'   => null,      //stored command for the device to process
            'valid'     => true,      //member request
            'available' => true,      //device status - remote shutdown,
            'member'    => null,      //member name
        ];

    }

    private function handleDoor($data)
    {
        $error = false;

        //Door entry is quite simple - this will just deal with lookups

        try {
            $this->keyFobAccess->verifyForEntry($data['tag'], 'main-door', $data['time']);

            $this->keyFobAccess->logSuccess();
        } catch (\Exception $e) {
            $error = true;
        }

        $cmd = $this->acsNodeRepository->popCommand($data['device']);

        if ( ! $error) {
            $responseData = ['member' => $this->keyFobAccess->getMemberName(), 'valid' => '1', 'cmd' => $cmd];
        } else {
            $responseData = ['valid' => '0', 'cmd' => $cmd];
        }

        return $this->sendResponse($responseData);
    }

    private function handleDevice($data)
    {
        $device = $this->acsNodeRepository->getByName($data['device']);

        if ($data['message'] == 'boot') {
            $this->acsNodeRepository->logBoot($data['device']);
        } elseif ($data['message'] == 'heartbeat') {
            $this->acsNodeRepository->logHeartbeat($data['device']);
        }

        //$member = $this->keyFobAccess->verifyForDevice($data['tag'], 'laser');

        $deviceStatus = 'ok';

        $responseData = ['deviceStatus' => $deviceStatus];

        return $this->sendResponse($responseData);
    }


    /**
     * System checkins are common across all devices
     * Record the time and return pending status messages
     *
     * @param $message
     * @param $device
     * @return Response
     */
    private function handleSystemCheckIn($message, $device, $service)
    {
        switch ($message) {
            case 'boot':
                $this->acsNodeRepository->logBoot($device);
                break;
            case 'heartbeat':
                $this->acsNodeRepository->logHeartbeat($device);
                break;
        }

        //The command comes from the database and will instruct the door entry system to clear its memory if set
        $cmd = $this->acsNodeRepository->popCommand($device);

        switch ($service) {
            case 'entry':
                //we don't have a system for this at the moment but could have a global shutdown option
                $deviceStatus = '1';
                break;
            case 'usage':
                //lookup the piece of equipment from the device id and get the status
                $deviceStatus = '1';
                break;
            default:
                $deviceStatus = '1';
        }

        $responseData = ['cmd' => $cmd, 'deviceStatus' => $deviceStatus];

        return $this->sendResponse($responseData);
    }

    /**
     * Json encode the response data and return
     *
     * @param array $responseData
     * @return \Response
     */
    private function sendResponse(array $responseData)
    {
        $responseData['time'] = time();
        $response             = \Response::json($responseData);
        $response->headers->set('Content-Length', strlen($response->getContent()));

        return $response;
    }

    private function logDetectedDevices($data)
    {
        //this isn't strictly a heartbeat but the updates occur at a regular interval so they will do
        $this->acsNodeRepository->logHeartbeat($data['device']);

        //See if any devices have been detected, if so log them
        foreach (array_keys($data['payload']['bluetooth_devices']) as $macAddress) {
            DetectedDevice::create([
                'type'         => 'bluetooth',
                'mac_address'  => $macAddress,
                'display_name' => $data['payload']['bluetooth_devices'][$macAddress],
            ]);
        }


    }

    private function returnMemberStatus($data)
    {
        try {
            $user = $this->keyFobAccess->verifyForEntry($data['tag'], 'main-door', $data['time']);
        } catch (\Exception $e) {
            $responseData = ['valid' => '0', 'cmd' => ''];
            return $this->sendResponse($responseData);
        }

        $responseData = ['member' => $this->keyFobAccess->getMemberName(), 'valid' => '1', 'cmd' => ''];

        return $this->sendResponse($responseData);
    }

} 