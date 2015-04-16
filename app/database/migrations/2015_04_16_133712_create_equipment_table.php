<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipment', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name', 50);
            $table->string('manufacturer', 50);
            $table->string('model_number', 50);
            $table->string('serial_number', 50);
            $table->string('colour', 50);
            $table->string('location', 50);
            $table->string('room', 50);
            $table->string('detail', 50);
            $table->string('key', 20);
            $table->string('device_key', 20);
            $table->text('description');
            $table->longText('help_text');
            $table->integer('owner_role_id');
            $table->boolean('requires_induction');
            $table->boolean('working');
            $table->boolean('permaloan');
            $table->integer('permaloan_user_id');
            $table->integer('access_fee');
            $table->boolean('photo');
            $table->boolean('archive');
            $table->date('obtained_at');
            $table->date('removed_at');
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
		Schema::drop('equipment');
	}

}
