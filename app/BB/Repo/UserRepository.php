<?php namespace BB\Repo;

class UserRepository extends DBRepository {

    /**
     * @var AccessLog
     */
    protected $model;

    function __construct(\User $model)
    {
        $this->model = $model;
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }
} 