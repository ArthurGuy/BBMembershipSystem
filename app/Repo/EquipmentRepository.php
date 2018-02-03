<?php namespace BB\Repo;

use BB\Entities\Equipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipmentRepository extends DBRepository
{

    /**
     * @var Equipment
     */
    protected $model;

    public function __construct(Equipment $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function getRequiresInduction()
    {
        return $this->model->where('requires_induction', true)->get();
    }

    public function getDoesntRequireInduction()
    {
        return $this->model->where('requires_induction', false)->get();
    }


    public function allPaid()
    {
        return $this->model->where('access_fee', '!=', 0)->get();
    }

    /**
     * Return a device by its slug
     * @param $slug
     * @return Equipment
     */
    public function findBySLug($slug)
    {
        $record = $this->model->where('slug', $slug)->first();
        if ($record) {
            return $record;
        }
        throw new ModelNotFoundException();
    }

    /**
     * Return a device by its slug
     * @param $slug
     * @return Equipment
     */
    public function findByDeviceKey($device)
    {
        return $this->model->where('device_key', $device)->firstOrFail();
    }
} 
