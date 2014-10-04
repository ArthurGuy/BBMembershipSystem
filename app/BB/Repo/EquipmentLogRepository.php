<?php namespace BB\Repo;

class EquipmentLogRepository extends DBRepository
{

    /**
     * @var EquipmentLog
     */
    protected $model;

    function __construct(\EquipmentLog $model)
    {
        $this->model = $model;
    }

    public function recordStart($userId, $keyFobId, $deviceKey, $notes = '')
    {
        $this->model->create(
            [
                'user_id'    => $userId,
                'key_fob_id' => $keyFobId,
                'device'     => $deviceKey,
                'active'     => 1,
                'notes'      => $notes
            ]
        );
    }

    public function findActiveSession($userId, $deviceKey)
    {
        $existingSession = $this->model->where('user_id', $userId)->where('device', $deviceKey)->where('active', 1)->orderBy('created_at', 'DESC')->first();
        if ($existingSession) {
            return $existingSession->id;
        }
        return false;
    }

    public function recordActivity($sessionId)
    {
        $existingSession = $this->model->findOrFail($sessionId);
        $existingSession->last_update = \DateTime();
        $existingSession->save();
    }

    public function endSession($sessionId)
    {
        $existingSession = $this->model->findOrFail($sessionId);
        $existingSession->finished = \DateTime();
        $existingSession->active = 0;
        $existingSession->save();
    }

} 