<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentRole extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::table('roles')->insert(['name'=>'equipment', 'title'=>'Manage Equipment', 'created_at'=>new DateTime()]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::table('roles')->where('name', '=', 'equipment')->delete();
	}

}
