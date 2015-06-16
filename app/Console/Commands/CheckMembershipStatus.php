<?php namespace BB\Console\Commands;

use Illuminate\Console\Command;

class CheckMembershipStatus extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'bb:check-memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the membership expiry dates and disable or email users';

    /**
     * @var \BB\Process\CheckMemberships
     */
    private $checkMemberships;
    /**
     * @var \BB\Process\CheckPaymentWarnings
     */
    private $checkPaymentWarnings;
    /**
     * @var \BB\Process\CheckLeavingUsers
     */
    private $checkLeavingUsers;
    /**
     * @var \BB\Process\CheckSuspendedUsers
     */
    private $checkSuspendedUsers;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->checkPaymentWarnings = \App::make('\BB\Process\CheckPaymentWarnings');
        $this->checkSuspendedUsers = \App::make('\BB\Process\CheckSuspendedUsers');
        $this->checkLeavingUsers = \App::make('\BB\Process\CheckLeavingUsers');
        $this->checkMemberships = \App::make('\BB\Process\CheckMemberships');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Users with a status of payment warning
        $this->info("Checking users with payment warnings");
        $this->checkPaymentWarnings->run();


        $this->info("Checking users with suspended status");
        $this->checkSuspendedUsers->run();


        //Users with a status of leaving
        $this->info("Checking users with a leaving status");
        $this->checkLeavingUsers->run();


        //This should occur last as it gives people 24 hours with a payment warning
        $this->info("Checking users subscription payments");
        $this->checkMemberships->run();
    }


}
