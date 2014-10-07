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


    public function getTrainersByEquipment()
    {
        $trainersRaw = $this->model->where('is_trainer', true)->get();
        $trainers = [];
        foreach ($trainersRaw as $trainer) {
            if (isset($trainer->user->name) && $trainer->user->active)
            {
                $trainers[$trainer->key][] = $trainer->user;
            }
        }
        return $trainers;
    }


    public function getUsersPendingInduction()
    {
        $usersRaw = $this->model->where('paid', true)->whereNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    public function getTrainedUsers()
    {
        $usersRaw = $this->model->where('paid', true)->whereNotNull('trained')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name) && $induction->user->active)
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }

    public function isUserTrained($userId, $device)
    {
        $record = $this->model->where('paid', true)->whereNotNull('trained')->where('user_id', $userId)->where('key', $device)->first();
        if ($record) {
            return true;
        } else {
            return false;
        }
    }
} 