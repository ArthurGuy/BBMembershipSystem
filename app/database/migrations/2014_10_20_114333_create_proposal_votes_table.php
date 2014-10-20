<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalVotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('proposal_votes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('proposal_id');
            $table->integer('user_id');
            $table->integer('vote')->nullable();    //-1, 0, 1
            $table->boolean('abstain');
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
		Schema::drop('proposal_votes');
	}

}
