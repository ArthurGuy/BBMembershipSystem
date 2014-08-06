<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
            $table->increments('id');
            $table->string('given_name', 100);
            $table->string('family_name', 100);
            $table->string('email')->unique();
            $table->string('secondary_email');
            $table->string('password');
            $table->string('type', 20); //member, admin
            $table->string('address_line_1', 100);
            $table->string('address_line_2', 100);
            $table->string('address_line_3', 100);
            $table->string('address_line_4', 100);
            $table->string('address_postcode', 10);
            $table->string('emergency_contact', 250);
            $table->string('notes');
            $table->boolean('active');
            $table->string('status', 20);
            $table->boolean('trusted');
            $table->boolean('key_holder');
            $table->string('payment_method', 20);   //gocardless,standing-order,cash,paypal,other
            $table->integer('payment_day');
            $table->double('monthly_subscription',10,2);
            $table->string('subscription_id', 128);
            $table->date('last_subscription_payment');
            $table->rememberToken();
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
		Schema::dropIfExists('users');
	}

}
