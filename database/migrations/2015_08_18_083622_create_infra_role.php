<?php

use BB\Entities\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfraRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create(['name'=>'infra', 'title'=>'Infrastructure']);
        Role::create(['name'=>'laser', 'title'=>'Laser Team']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', 'infra')->delete();
        Role::where('name', 'laser')->delete();
    }
}
