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
     * @return \CheckMembershipStatus
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
        //Users with a status of leaving
        $leavingProcess = new \BB\Process\CheckLeavingUsers();
        $leavingProcess->run();


        //Users with a status of payment warning
        //$paymentWarningProcess = new \BB\Process\CheckPaymentWarnings();
        //$paymentWarningProcess->run();


        //This should occur last as it gives people 24 hours with a payment warning
        $process = new \BB\Process\CheckMemberships();
        $process->run();
	}


}
