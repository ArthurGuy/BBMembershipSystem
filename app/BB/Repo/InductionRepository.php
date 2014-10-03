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
            if (isset($trainer->user->name))
            {
                $trainers[$trainer->key][] = $trainer->user;
            }
        }
        return $trainers;
    }


    public function getUsersPendingInduction()
    {
        $usersRaw = $this->model->where('paid', true)->where('trained', '0000-00-00 00:00:00')->get();
        $users = [];
        foreach ($usersRaw as $induction) {
            if (isset($induction->user->name))
            {
                $users[$induction->key][] = $induction->user;
            }
        }
        return $users;
    }
} 