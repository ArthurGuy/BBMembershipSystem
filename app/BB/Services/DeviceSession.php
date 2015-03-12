<?php namespace BB\Services;

use BB\Exceptions\ValidationException;
use BB\Repo\EquipmentLogRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeviceSession extends KeyFobAccess {



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
     * @var InductionRepository
     */
    private $inductionRepository;
    /**
     * @var EquipmentLogRepository
     */
    private $equipmentLogRepository;
    /**
     * @var Credit
     */
    private $bbCredit;


    public function __construct(EquipmentRepository $equipmentRepository, InductionRepository $inductionRepository, EquipmentLogRepository $equipmentLogRepository, Credit $bbCredit)
    {
        $this->equipmentRepository = $equipmentRepository;
        $this->inductionRepository = $inductionRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->bbCredit = $bbCredit;
    }


    public function validateData()
    {
        //Verify the keyfob, device key and action

        //Validate the action
        if (!in_array($this->action, $this->deviceActions)) {
            throw new ValidationException("Invalid Device Action");
        }
        //Validate the device
        try {
            $this->device = $this->equipmentRepository->findByKey($this->deviceKey);
        } catch (ModelNotFoundException $e) {
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


        //Make sure the member has enough money on their account
        $minimumBalance = $this->bbCredit->acceptableNegativeBalance('equipment-fee');
        if (($this->user->cash_balance + ($minimumBalance * 100)) <= 0) {
            throw new ValidationException("User doesn't have enough credit");
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
        if ($sessionId !== false) {
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
        if ($sessionId !== false) {
            $this->equipmentLogRepository->endSession($sessionId);
        } else {
            $sessionId = $this->equipmentLogRepository->recordStartCloseExisting($this->user->id, $this->keyFob->id, $this->deviceKey, 'inaccurate start');
            $this->equipmentLogRepository->endSession($sessionId);
        }
    }


}