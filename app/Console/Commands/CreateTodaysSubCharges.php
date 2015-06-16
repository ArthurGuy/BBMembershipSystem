<?php namespace BB\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateTodaysSubCharges extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'bb:create-todays-sub-charges {dayOffset=3}';

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

        $this->subscriptionChargeService = \App::make('\BB\Services\MemberSubscriptionCharges');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dayOffset = $this->argument('dayOffset');

        $targetDate = Carbon::now()->addDays($dayOffset);

        $this->info("Generating charges for " . $targetDate);

        $this->subscriptionChargeService->createSubscriptionCharges($targetDate);

        //in case yesterdays process failed we will rerun the past two days, this should pickup and stragglers
        $this->subscriptionChargeService->createSubscriptionCharges($targetDate->subDay()); //-1 day
        $this->subscriptionChargeService->createSubscriptionCharges($targetDate->subDay()); //-2 days
    }
}
