<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('reason', 20);       //subscription, induction, other
            $table->string('source', 20);       //gocardless, paypal, bank-transfer, etc...
            $table->string('source_id', 100)->unique();   //source payment reference
            $table->integer('user_id');
            $table->double('amount', 10, 2);
            $table->double('fee', 10, 2);
            $table->double('amount_minus_fee', 10, 2);
            $table->string('status', 20);      //pending, paid, cancelled, failed

            $table->index('status');
            $table->index('user_id');

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
		Schema::drop('payments');
	}

}
