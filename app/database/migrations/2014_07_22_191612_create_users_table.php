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
            $table->string('hash', 50)->nullable();
            $table->string('given_name', 100);
            $table->string('family_name', 100);
            $table->string('import_match_string', 50)->nullable();
            $table->string('email')->unique();
            $table->boolean('email_verified');
            $table->string('secondary_email')->nullable();
            $table->string('password')->nullable();
            $table->string('emergency_contact', 250)->nullable();
            $table->string('notes')->nullable();
            $table->boolean('active');
            $table->boolean('founder');
            $table->boolean('director');
            $table->string('status', 20);
            $table->boolean('trusted');
            $table->boolean('key_holder');
            $table->integer('key_deposit_payment_id')->nullable();
            $table->integer('storage_box_payment_id')->nullable();
            $table->boolean('induction_completed');
            $table->string('payment_method', 20)->nullable();   //gocardless,standing-order,cash,paypal,other
            $table->integer('payment_day');
            $table->integer('monthly_subscription')->nullable();
            $table->string('subscription_id', 128)->nullable();
            $table->date('last_subscription_payment')->nullable();
            $table->date('subscription_expires')->nullable();
            $table->integer('cash_balance')->default(0);
            $table->boolean('profile_private');
            $table->string('banned_reason')->nullable();
            $table->date('banned_date')->nullable();
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
