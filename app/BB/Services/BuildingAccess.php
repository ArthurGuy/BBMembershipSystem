<?php namespace BB\Services;

use BB\Exceptions\ValidationException;
use BB\Repo\AccessLogRepository;

class BuildingAccess extends KeyFobAccess {

    protected $systemMessage = false;

    /**
     * The valid actions for the device
     * @var array
     */
    private $deviceActions = ['delayed', ''];

    private $systemDeviceActions = ['boot', 'heartbeat', 'tamper'];

    private $devices = ['main-door'];


    public function decodeDeviceCommand($receivedData)
    {
        if ($this->determinSystemMessage($receivedData)) {

            $this->systemMessage = true;

            $this->decodeSystemMessage($receivedData);

        } else {

            $this->decodeAccessRequest($receivedData);

        }
    }

    /**
     * @return boolean
     */
    public function isSystemMessage()
    {
        return $this->systemMessage;
    }


    public function validateData()
    {
        //Verify the keyfob, device key and action

        //Validate the action
        if ($this->isSystemMessage()) {
            if (!in_array($this->action, $this->systemDeviceActions)) {
                throw new ValidationException("Invalid Device Action");
            }
            //Validate the device
            if (!in_array($this->deviceKey, $this->devices)) {
                throw new ValidationException("Invalid device key");
            }
            return;
        }

        //Validate the action
        if (!in_array($this->action, $this->deviceActions)) {
            throw new ValidationException("Invalid Device Action");
        }

        //Validate the device
        if (!in_array($this->deviceKey, $this->devices)) {
            throw new ValidationException("Invalid device key");
        }

        //Validate the key fob
        $this->keyFob = $this->lookupKeyFob($this->keyFobId);

        //Make sure the user is active
        $this->user = $this->keyFob->user()->first();
        if (!$this->user || !$this->user->active) {
            $this->logFailure();
            throw new ValidationException("Not a member");
        }

        if (!$this->user->trusted) {
            $this->logFailure();
            throw new ValidationException("Not a keyholder");
        }

        if (!$this->user->key_holder) {
            $this->logFailure();
            throw new ValidationException("Not a keyholder");
        }

        if (!($this->user->profile->profile_photo || $this->user->profile->profile_photo_on_wall)) {
            $this->logFailure();
            throw new ValidationException("Member not trusted");
        }

    }


    private function determinSystemMessage($receivedData)
    {
        return strpos($receivedData, ':') === 0;
    }

    private function decodeSystemMessage($receivedData)
    {
        //Remove the start : character
        $receivedData = substr($receivedData, 1, strlen($receivedData));

        //\Log::debug("System Message Received: " . $receivedData);

        //The system message consists of a device key and an action
        if (strpos($receivedData, '|') === false) {
            throw new ValidationException("Invalid System Message");
        }
        $messageParts = explode('|', $receivedData);

        if (count($messageParts) != 2) {
            throw new ValidationException("Invalid System Message");
        }
        $this->deviceKey = $messageParts[0];
        $this->action = $messageParts[1];
    }

    private function decodeAccessRequest($receivedData)
    {
        if (strpos($receivedData, '|') === false) {
            //No seperator character, just a key id
            $this->keyFobId = $receivedData;
        } else {
            $keyParts = explode('|', $receivedData);
            if (count($keyParts) != 2) {
                throw new ValidationException("Invalid System Message");
            }
            $this->keyFobId = $keyParts[0];
            $this->action = $keyParts[1];
            if ($this->action == 'delayed') {
                $this->messageDelayed = true;
            }
        }
    }

}