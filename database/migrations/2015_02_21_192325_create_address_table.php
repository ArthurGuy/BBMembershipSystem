<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('user_address', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('line_1', 100)->nullable();
            $table->string('line_2', 100)->nullable();
            $table->string('line_3', 100)->nullable();
            $table->string('line_4', 100)->nullable();
            $table->string('postcode', 10)->nullable();
            $table->boolean('approved');
            $table->string('hash', 50)->nullable();
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
        Schema::dropIfExists('user_address');
	}

}
