<?php namespace BB\Repo;

use Carbon\Carbon;

class EquipmentLogRepository extends DBRepository
{

    /**
     * @var \EquipmentLog
     */
    protected $model;

    function __construct(\EquipmentLog $model)
    {
        $this->model = $model;
        $this->perPage = 25;
    }

    /**
     * Record the start of device activity
     * @param integer $userId
     * @param integer $keyFobId
     * @param string  $deviceKey
     * @param string  $notes
     * @return integer
     */
    public function recordStart($userId, $keyFobId, $deviceKey, $notes = '')
    {
        $session             = new $this->model;
        $session->user_id    = $userId;
        $session->key_fob_id = $keyFobId;
        $session->device     = $deviceKey;
        $session->active     = 1;
        $session->started    = Carbon::now();
        $session->notes      = $notes;
        $session->save();
        return $session->id;
    }


    /**
     * Record a device start but close any existing user sessions first
     * @param integer $userId
     * @param integer $keyFobId
     * @param string  $deviceKey
     * @param string  $notes
     * @return integer
     */
    public function recordStartCloseExisting($userId, $keyFobId, $deviceKey, $notes = '')
    {
        $existingSessionId = $this->findActiveDeviceSession($deviceKey);
        if ($existingSessionId !== false) {
            $this->endSession($existingSessionId);
        }
        return $this->recordStart($userId, $keyFobId, $deviceKey, $notes);
    }

    /**
     * Locate a users active session
     * @param integer $userId
     * @param string $deviceKey
     * @return integer|false
     */
    public function findActiveUserSession($userId, $deviceKey)
    {
        $existingSession = $this->model->where('user_id', $userId)->where('device', $deviceKey)->where('active', 1)->orderBy('created_at', 'DESC')->first();
        if ($existingSession) {
            return $existingSession->id;
        }
        return false;
    }


    /**
     * Return an existing active session for the device, if any
     * @param $deviceKey
     * @return integer|false
     */
    public function findActiveDeviceSession($deviceKey)
    {
        $existingSession = $this->model->where('device', $deviceKey)->where('active', 1)->orderBy('created_at', 'DESC')->first();
        if ($existingSession) {
            return $existingSession->id;
        }
        return false;
    }

    /**
     * Record some activity on an existing session
     * @param integer $sessionId
     */
    public function recordActivity($sessionId)
    {
        $existingSession = $this->model->findOrFail($sessionId);
        $existingSession->last_update = Carbon::now();
        $existingSession->save();
    }

    /**
     * Record the end of a session
     * @param integer $sessionId
     * @param \DateTime    $finishedDate
     */
    public function endSession($sessionId, $finishedDate = null)
    {
        $existingSession = $this->model->findOrFail($sessionId);
        if ($finishedDate === null) {
            $finishedDate = Carbon::now();
        }
        $existingSession->finished = $finishedDate;
        $existingSession->active = 0;
        $existingSession->save();
    }

    /**
     * @param $deviceKey
     * @return mixed
     */
    public function getAllForEquipment($deviceKey)
    {
        return $this->model->where('device', $deviceKey)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Return records that have been checked over
     * @param $deviceKey
     * @return mixed
     */
    public function getFinishedForEquipment($deviceKey)
    {
        return $this->model->where('device', $deviceKey)->where('processed', true)->where('removed', false)->orderBy('created_at', 'DESC')->paginate($this->perPage);
    }

    /**
     * Return all records that are currently listed as active
     * @return mixed
     */
    public function getActiveRecords()
    {
        return $this->model->where('active', true)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Return all records that have been checked over
     * @return mixed
     */
    public function getFinishedRecords()
    {
        return $this->model->where('processed', true)->where('removed', false)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Return all records that have been checked over but not billed
     * @return mixed
     */
    public function getFinishedUnbilledRecords()
    {
        return $this->model->where('processed', true)->where('removed', false)->where('billed', false)->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Get all records that haven't been checked yet
     * @return mixed
     */
    public function getUnprocessedRecords()
    {
        return $this->model->where('processed', false)->orderBy('created_at', 'DESC')->get();
    }

} 