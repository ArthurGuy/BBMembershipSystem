<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 100);
            $table->string('device_id', 30);
            $table->string('queued_command', 100)->nullable();
            $table->boolean('monitor_heartbeat');
            $table->string('key', 100)->nullable();
            $table->dateTime('last_boot')->nullable();
            $table->dateTime('last_heartbeat')->nullable();
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
		Schema::drop('devices');
	}

}
