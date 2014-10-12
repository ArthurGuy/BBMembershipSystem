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
        $model = $this->model->with('roles');

        if ($params['showLeft']) {
            $model = $model->where('status', 'left');
        } else {
            $model = $model->where('status', '!=', 'left');
        }

        if ($this->isSortable($params)) {
            return $model->orderBy($params['sortBy'], $params['direction'])->simplePaginate($this->perPage);
        }
        return $model->simplePaginate($this->perPage);
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