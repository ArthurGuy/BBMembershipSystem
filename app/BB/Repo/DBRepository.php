<?php namespace BB\Repo;

abstract class DBRepository {

    /**
     * Eloquent model
     */
    protected $model;

    /**
     * @param $model
     */
    function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Fetch a record by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Return all the records in the repo
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Create a new record
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        return $this->model->create($data);
    }
} 