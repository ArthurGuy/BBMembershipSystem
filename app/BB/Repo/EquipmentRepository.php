<?php namespace BB\Repo;

class EquipmentRepository {

    public function all()
    {
        return [
            'laser' => (object)[
                'name' => 'Laser Cutter',
                'cost' => '50',
            ],
            'lathe' => (object)[
                'name' => 'Lathe',
                'cost' => '25',
            ],
            'welder' => (object)[
                'name' => 'Welder',
                'cost' => '20',
            ],
            'cnc' => (object)[
                'name' => 'CNC Router',
                'cost' => '25'
            ],
            //'3dprinter' => (object)[
            //        'name' => '3D Printer',
            //        'cost' => '0'
            //    ]
        ];
    }
} 