<?php namespace BB\Services;

use BB\Entities\KeyFob;
use BB\Exceptions\ValidationException;
use BB\Repo\AccessLogRepository;
use Carbon\Carbon;

class KeyFobAccess {

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
     * @var \User
     */
    protected $user;

    /**
     * @var AccessLogRepository
     */
    protected $accessLogRepository;


    protected $messageDelayed = false;


    protected $memberName;

    /**
     * @var Carbon
     */
    protected $time;


    public function __construct(AccessLogRepository $accessLogRepository)
    {
        $this->accessLogRepository = $accessLogRepository;
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

    /**
     * @return \User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Check a fob id is valid for door entry and return the member if it is
     * @param $keyId
     * @param string $doorName
     * @param $time
     * @return \User
     * @throws ValidationException
     */
    public function verifyForEntry($keyId, $doorName, $time)
    {
        $this->keyFob = $this->lookupKeyFob($keyId);

        $this->setAccessTime($time);

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

        $this->memberName = $this->user->given_name;


        //Fetch any commands that need to be returned to the device

        return $this->keyFob->user;
    }


    protected function lookupKeyFob($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        } catch (\Exception $e) {
            $keyId = substr('BB'.$keyId, 0, 12);
            try {
                $keyFob = KeyFob::lookup($keyId);
            } catch (\Exception $e) {
                throw new ValidationException("Key fob ID not valid");
            }
            return $keyFob;
        }
    }


    public function logFailure()
    {
        $log               = [];
        $log['key_fob_id'] = $this->keyFob->id;
        $log['user_id']    = $this->user->id;
        $log['service']    = 'main-door';
        $log['delayed']    = $this->messageDelayed;
        $log['response']   = 402;
        $log['created_at'] = $this->time;
        $this->accessLogRepository->logAccessAttempt($log);
    }

    public function logSuccess()
    {
        $log               = [];
        $log['key_fob_id'] = $this->keyFob->id;
        $log['user_id']    = $this->user->id;
        $log['service']    = 'main-door';
        $log['delayed']    = $this->messageDelayed;
        $log['created_at'] = $this->time;
        //OK
        $log['response']   = 200;
        $this->accessLogRepository->logAccessAttempt($log);
    }

    /**
     * @return mixed
     */
    public function getMemberName()
    {
        return $this->memberName;
    }

    /**
     * Set the time to a specific timestamp - the new entry system will be passing a local time with the requests
     * @param null $time
     */
    protected function setAccessTime($time=null)
    {
        if (!empty($time)) {
            $this->time = Carbon::createFromTimestamp($time);
        } else {
            $this->time = Carbon::now();
        }
    }

} 