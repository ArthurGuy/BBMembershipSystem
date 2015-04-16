<?php

use BB\Entities\Equipment;

class EquipmentSeeder extends DatabaseSeeder
{

    public function run()
    {
        DB::table('equipment')->delete();

        Equipment::create([
            'name'               => 'Laser Cutter',
            'key'                => 'laser',
            'device_key'         => 'laser',
            'requires_induction' => true,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => '50',
        ]);

        Equipment::create([
            'name'               => 'Lathe',
            'key'                => 'lathe',
            'device_key'         => 'lathe',
            'requires_induction' => true,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => 25,
        ]);

        Equipment::create([
            'name'               => 'Mig Welder',
            'key'                => 'welder',
            'device_key'         => 'welder',
            'requires_induction' => true,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => 20,
        ]);
        Equipment::create([
            'name'               => 'CNC Router',
            'key'                => 'cnc',
            'device_key'         => 'cnc',
            'requires_induction' => true,
            'working'            => false,
            'permaloan'          => false,
            'access_fee'         => 25,
        ]);
        Equipment::create([
            'name'               => 'Pillar Drill',
            'key'                => 'pillar-drill',
            'device_key'         => 'pillar-drill',
            'requires_induction' => false,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => 0,
        ]);
        Equipment::create([
            'name'               => 'Chop Saw',
            'key'                => 'chop-saw',
            'device_key'         => 'chop-saw',
            'requires_induction' => false,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => 0,
        ]);
        Equipment::create([
            'name'               => 'Band Saw',
            'key'                => 'band-saw',
            'device_key'         => 'band-saw',
            'requires_induction' => false,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => 0,
        ]);
        Equipment::create([
            'name'               => '3D Printer',
            'key'                => '3d-printer',
            'device_key'         => '3d-printer',
            'requires_induction' => false,
            'working'            => true,
            'permaloan'          => false,
            'access_fee'         => false,
        ]);
    }

}