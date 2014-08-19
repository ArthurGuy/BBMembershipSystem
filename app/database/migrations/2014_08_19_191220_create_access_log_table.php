<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('access_log', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->integer('key_fob_id');
            $table->string('response', 5);   //Status code
            $table->string('service', 50);
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
		Schema::drop('access_log');
	}

}
