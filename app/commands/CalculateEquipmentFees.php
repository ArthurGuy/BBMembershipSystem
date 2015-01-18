<?php

use BB\Services\DeviceCharge;
use Illuminate\Console\Command;


class CalculateEquipmentFees extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:calculate-equipment-fees';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create the fees for equipment usage';


	/**
	 * @var \BB\Repo\EquipmentLogRepository
	 */
	protected $equipmentLogRepository;

	/**
	 * @var \BB\Repo\PaymentRepository
	 */
	protected $paymentRepository;


	/**
	 * Create a new command instance.
	 *
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
		$deviceCharging = new DeviceCharge();
		$deviceCharging->calculatePendingDeviceFees();
	}


}
