<?php namespace BB\Services;

use BB\Exceptions\ValidationException;
use BB\Repo\EquipmentLogRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;

class DeviceSession {

    /**
     * The key fob string
     * @var string
     */
    private $keyFobId;

    /**
     * The key fob record
     * @var
     */
    private $keyFob;

    /**
     * The key for the selected device
     * @var string
     */
    private $deviceKey;

    /**
     * The selected device record
     * The device that's being acted apon
     * @var
     */
    private $device;

    /**
     * The action of the current session
     * @var string
     */
    private $action;

    /**
     * The valid actions for the device
     * @var array
     */
    private $deviceActions = ['start', 'ping', 'end'];

    /**
     * @var
     */
    private $equipmentRepository;
    /**
     * @var \User
     */
    private $user;
    /**
     * @var InductionRepository
     */
    private $inductionRepository;
    /**
     * @var EquipmentLogRepository
     */
    private $equipmentLogRepository;


    public function __construct(EquipmentRepository $equipmentRepository, InductionRepository $inductionRepository, EquipmentLogRepository $equipmentLogRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
        $this->inductionRepository = $inductionRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
    }


    /**
     * @param string $keyFobId
     */
    public function setKeyFobId($keyFobId)
    {
        $this->keyFobId = $keyFobId;
    }

    /**
     * @param string $deviceKey
     */
    public function setDeviceKey($deviceKey)
    {
        $this->deviceKey = $deviceKey;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getKeyFobId()
    {
        return $this->keyFobId;
    }

    /**
     * @return
     */
    public function getKeyFob()
    {
        return $this->keyFob;
    }

    /**
     * @return string
     */
    public function getDeviceKey()
    {
        return $this->deviceKey;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function validateData()
    {
        //Verify the keyfob, device key and action

        //Validate the action
        if (!in_array($this->action, $this->deviceActions)) {
            throw new ValidationException("Invalid Device Action");
        }
        //Validate the device
        $this->device = $this->equipmentRepository->findByKey($this->deviceKey);
        if (!$this->device) {
            throw new ValidationException("Invalid Device Key");
        }
        //Confirm the device is working
        if (!$this->device->working) {
            throw new ValidationException("Device Not Working");
        }
        //Validate the key fob
        $this->keyFob = $this->lookupKeyFob($this->keyFobId);

        //Make sure the user is active
        $this->user = $this->keyFob->user()->first();
        if (!$this->user || !$this->user->active) {
            throw new ValidationException("User Invalid");
        }

        //Make sure the user is allowed to use the device
        if ($this->device->requires_training) {
            //Verify the user has training
            if (!$this->inductionRepository->isUserTrained($this->user->id, $this->deviceKey)) {
                throw new ValidationException("User Not Trained");
            }
        }
    }

    public function decodeDeviceCommand($receivedData)
    {
        $dataPacket = explode('|', $receivedData);
        if (count($dataPacket) != 3) {
            throw new ValidationException("Invalid Device String");
        }

        $this->keyFobId = trim(strtolower($dataPacket[0]));
        $this->deviceKey = trim(strtolower($dataPacket[1]));
        $this->action = trim(strtolower($dataPacket[2]));
    }

    private function lookupKeyFob($keyId)
    {
        try {
            $keyFob = \KeyFob::lookup($keyId);
            return $keyFob;
        } catch (\Exception $e) {
            $keyId = substr('BB'.$keyId, 0, 12);
            try {
                $keyFob = \KeyFob::lookup($keyId);
            } catch (\Exception $e) {
                throw new ValidationException("Key fob ID not valid");
            }
            return $keyFob;
        }
    }

    public function processAction()
    {
        if ($this->action == 'start') {
            $this->processStartAction();
        } elseif ($this->action == 'ping') {
            $this->processPingAction();
        } elseif ($this->action == 'end') {
            $this->processEndAction();
        }
    }

    private function processStartAction()
    {
        $this->equipmentLogRepository->recordStartCloseExisting($this->user->id, $this->keyFob->id, $this->deviceKey);
    }

    private function processPingAction()
    {
        $sessionId = $this->equipmentLogRepository->findActiveUserSession($this->user->id, $this->deviceKey);
        if ($sessionId) {
            $this->equipmentLogRepository->recordActivity($sessionId);
        } else {
            //We don't have an active session, there could have been a network failure so start now
            $this->equipmentLogRepository->recordStartCloseExisting($this->user->id, $this->keyFob->id, $this->deviceKey, 'inaccurate start');
        }
    }

    private function processEndAction()
    {
        //Close the session
        $sessionId = $this->equipmentLogRepository->findActiveUserSession($this->user->id, $this->deviceKey);
        if ($sessionId) {
            $this->equipmentLogRepository->endSession($sessionId);
        } else {
            $sessionId = $this->equipmentLogRepository->recordStartCloseExisting($this->user->id, $this->keyFob->id, $this->deviceKey, 'inaccurate start');
            $this->equipmentLogRepository->endSession($sessionId);
        }
    }

    /**
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }


}