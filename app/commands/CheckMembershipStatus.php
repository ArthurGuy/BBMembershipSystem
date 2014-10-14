<?php

use Illuminate\Console\Command;

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
        //Users with a status of payment warning
        //$this->info("Checking users with payment warnings");
        //$paymentWarningProcess = new \BB\Process\CheckPaymentWarnings();
        //$paymentWarningProcess->run();


        //Users with a status of leaving
        $this->info("Checking users with a leaving status");
        $leavingProcess = new \BB\Process\CheckLeavingUsers();
        $leavingProcess->run();


        //This should occur last as it gives people 24 hours with a payment warning
        $this->info("Checking users subscription payments");
        $process = new \BB\Process\CheckMemberships();
        $process->run();
	}


}
