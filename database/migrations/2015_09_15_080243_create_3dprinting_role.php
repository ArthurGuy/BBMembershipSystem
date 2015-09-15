<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create3dprintingRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::create(['name'=>'3dprinting', 'title'=>'3D Printing', 'description' => 'Managing and helping with Build Brighton\'s 3D printers']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Role::where('name', '3dprinting')->delete();
    }
}
