<?php namespace BB\Services;

use BB\Exceptions\ValidationException;

abstract class KeyFobAccess {

    /**
     * The key fob string
     * @var string
     */
    protected  $keyFobId;

    /**
     * The key fob record
     * @var
     */
    protected $keyFob;

    /**
     * The key for the selected device
     * @var string
     */
    protected $deviceKey;

    /**
     * The selected device record
     * The device that's being acted apon
     * @var
     */
    protected $device;

    /**
     * The action of the current session
     * @var string
     */
    protected $action;



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


    protected function lookupKeyFob($keyId)
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

} 