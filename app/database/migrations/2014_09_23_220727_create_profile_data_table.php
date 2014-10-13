<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile_data', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id');
            $table->string('twitter', 50)->nullable();
            $table->string('facebook', 50)->nullable();
            $table->string('google_plus', 50)->nullable();
            $table->string('github', 50)->nullable();
            $table->string('irc', 50)->nullable();
            $table->string('website', 10)->nullable();
            $table->string('tagline', 250)->nullable();
            $table->text('description')->nullable();
            $table->string('skills_array', 250)->nullable();
            $table->boolean('profile_photo');
            $table->boolean('profile_photo_private');
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
		Schema::drop('profile_data');
	}

}
