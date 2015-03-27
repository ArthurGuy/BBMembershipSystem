<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BillMembers extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:bill-members';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Bill members based on the sub charge records';

    /**
     * @var \BB\Services\MemberSubscriptionCharges
     */
    private $subscriptionChargeService;

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();

        $this->subscriptionChargeService = App::make('\BB\Services\MemberSubscriptionCharges');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->subscriptionChargeService->billMembers();
	}

}
