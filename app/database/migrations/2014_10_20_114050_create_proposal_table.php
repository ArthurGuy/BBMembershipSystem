<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposals', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('title', 100);
            $table->longText('description');
            $table->integer('user_id'); //proposer
            $table->date('end_date');
            $table->integer('votes_cast');
            $table->integer('votes_for');
            $table->integer('votes_against');
            $table->integer('abstentions');
            $table->boolean('quorum');
            $table->integer('result');
            $table->boolean('processed');
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
		Schema::drop('proposals');
	}

}
