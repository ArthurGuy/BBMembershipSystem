<?php namespace BB\Repo;

use BB\Entities\Equipment;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EquipmentRepository extends DBRepository {

    /**
     * @var Equipment
     */
    protected $model;

    function __construct(Equipment $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    /*
    public function all()
    {
        return [
            'laser' => (object)[
                'name' => 'Laser Cutter',
                'cost' => '50',
                'working' => 1,
                'requires_training' => 1,
            ],
            'lathe' => (object)[
                'name' => 'Lathe',
                'cost' => '25',
                'working' => 1,
                'requires_training' => 1,
            ],
            'welder' => (object)[
                'name' => 'Welder',
                'cost' => '20',
                'working' => 1,
                'requires_training' => 1,
            ],
            'cnc' => (object)[
                'name' => 'CNC Router',
                'cost' => '25',
                'working' => 1,
                'requires_training' => 1,
            ],
            'pillar-drill' => (object)[
                'name' => 'Pillar Drill',
                'cost' => 0,
                'working' => 1,
                'requires_training' => 0,
            ],
            'chop-saw' => (object)[
                'name' => 'Chop Saw',
                'cost' => 0,
                'working' => 1,
                'requires_training' => 0,
            ],
            'band-saw' => (object)[
                'name' => 'Band Saw',
                'cost' => 0,
                'working' => 1,
                'requires_training' => 1,
            ],
            '3dprinter' => (object)[
                'name' => '3D Printer',
                'cost' => '0',
                'working' => 1,
                'requires_training' => 0,
            ]
        ];
    }
    */

    public function allPaid()
    {
        return $this->model->where('access_fee', '!=', 0)->get();
    }

    /**
     * Return a device by its string key
     * @param $key
     * @return bool|object
     */
    public function findByKey($key)
    {
        $record = $this->model->where('key', $key)->first();
        if ($record) {
            return $record;
        }
        throw new ModelNotFoundException();
    }
} 