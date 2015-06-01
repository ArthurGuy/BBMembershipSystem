<?php namespace BB\Repo;

abstract class DBRepository
{

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    protected $perPage;
    protected $memberId;
    protected $startDate = null;
    protected $endDate = null;

    /**
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Fetch a record by id
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Return all the records in the repo
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Create a new record
     * @param $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        return $this->model->create($data);
    }


    /**
     * Delete a record
     * @param $recordId
     * @return bool|null
     * @throws \Exception
     */
    public function delete($recordId)
    {
        $this->getById($recordId)->delete();
    }


    /**
     * Update a record
     * @param $recordId
     * @param $recordData
     * @return mixed
     */
    public function update($recordId, $recordData)
    {
        return $this->getById($recordId)->update($recordData);
    }

    /**
     * Return a sortable paginated list
     *
     * @param array $params
     * @return mixed
     */
    public function getPaginated(array $params)
    {
        $model = $this->model;

        if ($this->hasMemberFilter()) {
            $model = $model->where('user_id', $this->memberId);
        }

        if ($this->isSortable($params)) {
            return $model->orderBy($params['sortBy'], $params['direction'])->paginate($this->perPage);
        }

        return $model->paginate($this->perPage);
    }

    /**
     * @param array $params
     * @return bool
     */
    public function isSortable(array $params)
    {
        return isset($params['sortBy']) && isset($params['direction']) && $params['sortBy'] && $params['direction'];
    }

    /**
     * @param integer $perPage
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
    }

    /**
     * Used for the getPaginated and getTotalAmount method
     * @param $memberFilter
     */
    public function memberFilter($memberFilter)
    {
        $this->memberId = $memberFilter;
    }

    protected function hasMemberFilter()
    {
        return ! is_null($this->memberId);
    }
} 