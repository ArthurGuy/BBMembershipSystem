<?php namespace BB\Repo;

class UserRepository extends DBRepository {

    /**
     * @var AccessLog
     */
    protected $model;

    function __construct(\User $model)
    {
        $this->model = $model;

        $this->perPage = 100;
    }

    public function getActive()
    {
        return $this->model->active()->get();
    }

    public function getPaginated(array $params)
    {
        if ($this->isSortable($params)) {
            return $this->model->with('roles')->orderBy($params['sortBy'], $params['direction'])->paginate($this->perPage);
        }
        return $this->model->with('roles')->paginate($this->perPage);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isSortable(array $params)
    {
        return isset($params['sortBy']) && isset($params['direction']) && $params['sortBy'] && $params['direction'];
    }


}