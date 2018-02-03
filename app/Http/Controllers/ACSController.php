<?php namespace BB\Http\Controllers;

use BB\Entities\ACSNode;
use BB\Entities\DetectedDevice;
use BB\Entities\User;
use BB\Events\MemberActivity;
use BB\Exceptions\ValidationException;
use BB\Repo\ACSNodeRepository;
use BB\Repo\EquipmentLogRepository;
use BB\Validators\ACSValidator;
use Exception;

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
    /**
     * @var EquipmentLogRepository
     */
    private $equipmentLogRepository;

    function __construct(ACSNodeRepository $acsNodeRepository, ACSValidator $ACSValidator, \BB\Services\KeyFobAccess $keyFobAccess, EquipmentLogRepository $equipmentLogRepository) {
        $this->acsNodeRepository = $acsNodeRepository;
        $this->ACSValidator     = $ACSValidator;
        $this->keyFobAccess     = $keyFobAccess;
        $this->equipmentLogRepository = $equipmentLogRepository;
    }

    public function store()
    {
        $data = \Request::only('device', 'service', 'message', 'tag', 'time', 'payload', 'signature', 'nonce', 'session_id');

        //device = the node id from the acs table - unique to each acs
        //service = what the request is for, entry, usage, consumable
        //message = system message, heartbeat, boot
        //tag = the keyfob id
        //time = the time of the action
        //payload = any extra data relavent to the request
        //signature = an encoded value generated using a secret key - oauth style
        //nonce = a unique value suitable to stop replay attacks
        //session_id = the id of the current session the user is maintaining

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
                return $this->handleStatus($data);

                break;
            default:
                \Log::debug(json_encode($data));
        }

    }

    private function handleStatus($data)
    {
        try {
            /** @var User $user */
            $user = $this->keyFobAccess->verifyForEntry($data['tag'], 'main-door', $data['time']);
        } catch (\Exception $e) {
            return $this->sendResponse(404, []);
        }

        return $this->sendResponse(200, ['member' => $user->given_name]);
    }

    private function handleDoor($data)
    {
        //Door entry is quite simple - this will just deal with lookups

        try {
            /** @var User $user */
            $user = $this->keyFobAccess->verifyForEntry($data['tag'], 'main-door', $data['time']);
            $this->keyFobAccess->logSuccess();
        } catch (\Exception $e) {
            return $this->sendResponse(404, []);
        }

        return $this->sendResponse(200, ['member' => $user->given_name]);
    }


    private function handleDevice($data)
    {
        $sessionId = null;
        try {
            $device = ACSNode::where('device_id', $data['device'])->firstOrFail();
            if ($device->entry_device) {
                return $this->sendResponse(400, ['message' => 'This is an entry device only']);
            } else {
                $member = $this->keyFobAccess->verifyForDevice($data['tag'], $data['device'], $data['time']);
                $keyFob = $this->keyFobAccess->getKeyFob();

                if ($data['message'] == 'start') {
                    $sessionId = $this->equipmentLogRepository->recordStartCloseExisting($keyFob->user->id, $keyFob->id, $data['device']);
                    event(new MemberActivity($keyFob, $data['device']));
                } elseif ($data['message'] == 'stop') {
                    $this->equipmentLogRepository->endSession($data['session_id']);
                }
            }
        } catch (ValidationException $e) {
            return $this->sendResponse(400, ['message' => $e->getMessage()]);
        }
        return $this->sendResponse(200, ['member' => $member->given_name, 'session_id' => $sessionId]);
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

        return $this->sendResponse(200, $responseData);
    }

    /**
     * Json encode the response data and return
     *
     * @param int   $statusCode
     * @param array $responseData
     *
     * @return \Response
     */
    private function sendResponse($statusCode = 200, array $responseData)
    {
        $responseData['time'] = time();
        $response = response()->json($responseData, $statusCode);
        $response->headers->set('Content-Length', strlen($response->getContent()));
        return $response;
    }

} 
