<?php

use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CreateTodaysSubCharges extends Command
{

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
     * @var \BB\Services\MemberSubscriptionCharges
     */
    private $subscriptionChargeService;

    /**
     * Create a new command instance.
     *
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
        $dayOffset = $this->argument('dayOffset');

        $targetDate = Carbon::now()->addDays($dayOffset);

        $this->info("Generating charges for " . $targetDate);

        $this->subscriptionChargeService->createSubscriptionCharges($targetDate);

        //in case yesterdays process failed we will rerun the past two days, this should pickup and stragglers
        $this->subscriptionChargeService->createSubscriptionCharges($targetDate->subDay()); //-1 day
        $this->subscriptionChargeService->createSubscriptionCharges($targetDate->subDay()); //-2 days
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('dayOffset', InputArgument::OPTIONAL, 'Day Offset', 3),
        );
    }
}
