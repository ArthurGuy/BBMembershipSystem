<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipment_log', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->integer('key_fob_id');
            $table->string('device', 50);
            $table->boolean('active');    //in progress?
            $table->dateTime('started')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->dateTime('finished')->nullable();
            $table->string('notes');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('equipment_log');
	}

}
