<?php namespace BB\Repo;

class EquipmentRepository {

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
            //'3dprinter' => (object)[
            //        'name' => '3D Printer',
            //        'cost' => '0'
            //    ]
        ];
    }

    /**
     * Return a device by its string key
     * @param $key
     * @return bool|object
     */
    public function findByKey($key)
    {
        $equipment = $this->all();
        if (isset($equipment[$key])) {
            return $equipment[$key];
        }
        return false;
    }
} 