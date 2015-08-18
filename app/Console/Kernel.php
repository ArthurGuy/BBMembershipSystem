<?php namespace BB\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client as HttpClient;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CheckMembershipStatus::class,
        Commands\RecalculateUserBalances::class,
        Commands\CalculateProposalVotes::class,
        Commands\CheckFixEquipmentLog::class,
        Commands\CalculateEquipmentFees::class,
        Commands\CreateTodaysSubCharges::class,
        Commands\BillMembers::class,
        Commands\CheckDeviceOnlineStatuses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('bb:calculate-proposal-votes')->hourly()
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/U6l211ROnnZR1vI'); } );

        $schedule->command('bb:check-memberships')->dailyAt('06:00')
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/76TWKkWBBpaIyOe'); } );

        $schedule->command('bb:fix-equipment-log')->hourly()
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/nxi4SJkwZpIAkBv'); } );

        $schedule->command('bb:calculate-equipment-fees')->dailyAt('02:00')
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/tFdRdkUoqSa8X66'); } );

        $schedule->command('bb:update-balances')->dailyAt('03:00')
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/TSmoQANsHU9jbtU'); } );

        $schedule->command('bb:create-todays-sub-charges')->dailyAt('01:00')
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/wSIUR1E2wjVBzPg'); } );

        $schedule->command('bb:bill-members')->dailyAt('01:30')
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/nxAz59P6LXlu2P1'); } );

        $schedule->command('device:check-online')->hourly()
            ->then( function () { $this->pingIfProduction('http://beats.envoyer.io/heartbeat/WU4zql7LwZs1CzT'); } );
    }

    protected function pingIfProduction($url)
    {
        if (env('APP_ENV', 'production') == 'production') {
            (new HttpClient)->get($url);
        }
    }

}
