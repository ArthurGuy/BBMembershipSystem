<?php

use BB\Entities\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class UpdateInfraRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::findByName('infra')->update(['name' => 'acs', 'title' => 'Access Control', 'description' => 'Access control systems and all things RFID']);
        Role::create(['name'=>'infra', 'title'=>'Infrastructure', 'description' => 'Building infrastructure, electrics, lighting, etc...']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::findByName('acs')->update(['name' => 'infra', 'title' => 'Infrastructure', 'description' => 'Access control systems and all things RFID']);
        Role::where('name', 'infra')->delete();
    }
}
