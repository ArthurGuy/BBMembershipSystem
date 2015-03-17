<?php

use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTodaysSubCharges extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:create-todays-sub-charges';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create all the subscription changes for today';

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;

    /**
     * @var \BB\Repo\SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    /**
	 * Create a new command instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();

        $this->userRepository = App::make('\BB\Repo\UserRepository');
        $this->subscriptionChargeRepository = App::make('\BB\Repo\SubscriptionChargeRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$users = $this->userRepository->getActive();

        $targetDate = Carbon::now()->addDays(3);

        foreach ($users as $user) {
            if (($user->payment_day == $targetDate->day) && (!$this->subscriptionChargeRepository->chargeExists($user->id, $targetDate))) {
                $this->subscriptionChargeRepository->createCharge($user->id, $targetDate, $user->monthly_subscription);
            }
        }
	}

}
