<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInductionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inductions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->string('key');
            $table->boolean('paid');
            $table->integer('payment_id');
            $table->boolean('active');
            $table->dateTime('trained');
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
		Schema::drop('inductions');
	}

}
