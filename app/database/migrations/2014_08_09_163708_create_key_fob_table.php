<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeyFobTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('key_fobs', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->string('key_id');
            $table->boolean('active');
            $table->boolean('lost');
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
		Schema::drop('key_fobs');
	}

}
