<?php namespace BB\Repo;

class EquipmentLogRepository extends DBRepository
{

    /**
     * @var \EquipmentLog
     */
    protected $model;

    function __construct(\EquipmentLog $model)
    {
        $this->model = $model;
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
        $session->Active     = 1;
        $session->notes      = $notes;
        $session->save();
        return $session->id;
    }

    /**
     * Locate a users active session
     * @param integer $userId
     * @param string $deviceKey
     * @return integer|bool
     */
    public function findActiveSession($userId, $deviceKey)
    {
        $existingSession = $this->model->where('user_id', $userId)->where('device', $deviceKey)->where('active', 1)->orderBy('created_at', 'DESC')->first();
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
        $existingSession->last_update = \DateTime();
        $existingSession->save();
    }

    /**
     * Record the end of a session
     * @param integer $sessionId
     */
    public function endSession($sessionId)
    {
        $existingSession = $this->model->findOrFail($sessionId);
        $existingSession->finished = \DateTime();
        $existingSession->active = 0;
        $existingSession->save();
    }

} 