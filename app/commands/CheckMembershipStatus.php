<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckMembershipStatus extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:check-memberships';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check the membership expiry dates and disable or email users';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$process = new \BB\Process\CheckMemberships();
        $process->run();
	}


}
