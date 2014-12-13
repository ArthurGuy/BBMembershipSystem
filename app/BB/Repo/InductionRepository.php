<?php namespace BB\Repo;

class InductionRepository extends DBRepository {

    /**
     * @var Induction
     */
    protected $model;

    function __construct(\Induction $model)
    {
        $this->model = $model;
    }


    /**
     * @return array
     */
    public function getTrainersByEquipment()
    {
        $trainersRaw = $this->model->with('user', 'user.profile')->where('is_trainer', true)->get();
        $trainers = [];
        foreach ($trainersRaw as $trainer) {
            if (isset($trainer->user->name) && $trainer->user->active)
            {
                $trainers[$trainer->key][] = $trainer->user;
            }
        }
        return $trainers;
    }

    /**
     * @param $deviceId
     * @return array
     */
    public function getTrainersForEquipment($deviceId)
    {
        return $this->model->with('user', 'user.profile')->where('is_trainer', true)->where('key', $deviceId)->get();
    }


    /**
     * @return array
     */
    public function getUsersPendingInduction()
    {
        $usersRaw = $this->model->with('user', 'user.profile')->where('paid', true)->whereNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    /**
     * @return array
     */
    public function getTrainedUsers()
    {
        $usersRaw = $this->model->with('user', 'user.profile')->where('paid', true)->whereNotNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    /**
     * @param $userId
     * @param $device
     * @return bool
     */
    public function isUserTrained($userId, $device)
    {
        $record = $this->model->with('user', 'user.profile')->where('paid', true)->whereNotNull('trained')->where('user_id', $userId)->where('key', $device)->first();
        if ($record) {
            return true;
        } else {
            return false;
        }
    }
} 