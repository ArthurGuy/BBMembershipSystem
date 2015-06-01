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
            $table->string('manufacturer', 50)->nullable();
            $table->string('model_number', 50)->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->string('colour', 50)->nullable();
            $table->string('room', 50)->nullable();
            $table->string('detail', 50)->nullable();
            $table->string('key', 20)->nullable();
            $table->string('device_key', 20)->nullable();
            $table->text('description')->nullable();
            $table->longText('help_text')->nullable();
            $table->string('ppe', 255)->nullable();
            $table->integer('managing_role_id')->nullable();
            $table->boolean('requires_induction')->default(0);
            $table->string('induction_category', 20)->nullable();
            $table->boolean('working')->default(1);
            $table->boolean('permaloan')->default(0);
            $table->integer('permaloan_user_id')->nullable();
            $table->integer('access_fee')->default(0);
            $table->integer('usage_cost')->default(0);
            $table->enum('usage_cost_per', ['hour', 'gram']);
            $table->text('photos');
            $table->boolean('archive')->default(0);
            $table->string('asset_tag_id', 50)->nullable();
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
