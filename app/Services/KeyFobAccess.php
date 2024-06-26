<?php namespace BB\Services;

use BB\Entities\Equipment;
use BB\Entities\KeyFob;
use BB\Entities\User;
use BB\Events\MemberActivity;
use BB\Exceptions\ValidationException;
use BB\Repo\ActivityRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KeyFobAccess
{

    /**
     * The key fob string
     * @var string
     */
    protected  $keyFobId;

    /**
     * The key fob record
     * @var KeyFob
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
     * @var User
     */
    protected $user;

    /**
     * @var ActivityRepository
     */
    protected $activityRepository;


    protected $messageDelayed = false;


    protected $memberName;

    /**
     * @var Carbon
     */
    protected $time;
    /**
     * @var EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var InductionRepository
     */
    private $inductionRepository;
    /**
     * @var Credit
     */
    private $bbCredit;


    public function __construct(ActivityRepository $activityRepository, EquipmentRepository $equipmentRepository, InductionRepository $inductionRepository, Credit $bbCredit)
    {
        $this->activityRepository = $activityRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->inductionRepository = $inductionRepository;
        $this->bbCredit = $bbCredit;
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
     * @return User
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
        $this->user = $this->keyFob->user;
        if ( ! $this->user || ! $this->user->active) {
            $this->logFailure();
            throw new ValidationException('Not an active member');
        }

        if ( ! $this->user->trusted) {
            $this->logFailure();
            throw new ValidationException('Member is not marked trusted');
        }

        if ( ! $this->user->key_holder) {
            $this->logFailure();
            throw new ValidationException('Member is not a key holder');
        }

        if ( ! ($this->user->profile->profile_photo || $this->user->profile->profile_photo_on_wall)) {
            $this->logFailure();
            throw new ValidationException('Member photo not uploaded and approved');
        }

        $this->memberName = $this->user->given_name;


        //Fetch any commands that need to be returned to the device

        return $this->keyFob->user;
    }


    public function verifyForDevice($keyId, $device, $time)
    {
        $this->keyFob = $this->lookupKeyFob($keyId);
        $this->setAccessTime($time);

        //Make sure the user is active
        $this->user = $this->keyFob->user;
        if ( ! $this->user || ! $this->user->active) {
            $this->logFailure();
            throw new ValidationException('Not an active member');
        }

        //Validate the device
        try {
            /** @var Equipment $equipment */
            $equipment = $this->equipmentRepository->findByDeviceKey($device);
        } catch (ModelNotFoundException $e) {
            throw new ValidationException('Invalid Device Key');
        }

        //Make sure the user is allowed to use the device
        if ($equipment->requires_induction) {
            //Verify the user has training
            if ( ! $this->inductionRepository->isUserTrained($this->user->id, $equipment->slug)) {
                throw new ValidationException('User Not Trained');
            }
        }


        //Make sure the member has enough money on their account
        $minimumBalance = $this->bbCredit->acceptableNegativeBalance('equipment-fee');
        if (($this->user->cash_balance + ($minimumBalance * 100)) <= 0) {
            throw new ValidationException('User doesn\'t have enough credit');
        }

        return $this->user;
    }

    public function lookupKeyFob($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
            return $keyFob;
        } catch (\Exception $e) {
            $keyId = substr('BB' . $keyId, 0, 12);
            try {
                $keyFob = KeyFob::lookup($keyId);
            } catch (\Exception $e) {
                throw new ValidationException('Key fob ID not valid');
            }
            return $keyFob;
        }
    }


    /**
     * @param $keyId
     *
     * @return KeyFob
     */
    public function extendedKeyFobLookup($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
        } catch (\Exception $e) {
            $oldTagId = substr('BB' . $keyId, 0, 12);
            try {
                $keyFob = KeyFob::lookup($oldTagId);
            } catch (\Exception $e) {

                //The ids coming in will have no checksum (last 2 digits) and the first digit will be incorrect

                //Remove the first character
                $keyId = substr($keyId, 1);

                try {
                    $keyFob = KeyFob::lookupPartialTag($keyId);
                } catch (\Exception $e) {
                    throw new ModelNotFoundException('Key fob ID not found');
                }
            }
        }
        return $keyFob;
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
        $this->activityRepository->logAccessAttempt($log);

    }

    public function logSuccess()
    {
        event(new MemberActivity($this->keyFob, 'main-door', $this->time, $this->messageDelayed));

        /*
        $activity = $this->activityRepository->recordMemberActivity($this->user->id, $this->keyFob->id, 'main-door', $this->time);

        if ($this->messageDelayed) {
            $activity->delayed = true;
            $activity->save();
        }
        */
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
    protected function setAccessTime($time = null)
    {
        if ( ! empty($time)) {
            $this->time = Carbon::createFromTimestamp($time);
            $this->messageDelayed = true;
        } else {
            $this->time = Carbon::now();
        }
    }

} 
