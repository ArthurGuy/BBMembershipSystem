<?php namespace BB\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'BB\Console\Commands\Inspire',
		'BB\Console\Commands\CheckMembershipStatus',
		'BB\Console\Commands\RecalculateUserBalances',
		'BB\Console\Commands\CalculateProposalVotes',
		'BB\Console\Commands\CheckFixEquipmentLog',
		'BB\Console\Commands\CalculateEquipmentFees',
		'BB\Console\Commands\CreateTodaysSubCharges',
		'BB\Console\Commands\BillMembers',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();
	}

}
