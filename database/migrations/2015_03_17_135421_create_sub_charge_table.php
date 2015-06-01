<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubChargeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscription_charge', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->date('charge_date');
            $table->date('payment_date');
            $table->integer('amount');
            $table->enum('status', ['pending', 'due', 'processing', 'paid', 'cancelled']);
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
		Schema::drop('subscription_charge');
	}

}
