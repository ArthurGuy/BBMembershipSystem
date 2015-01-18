<?php

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
		$this->equipmentLogRepository = App::make('\BB\Repo\EquipmentLogRepository');
		$this->paymentRepository = App::make('\BB\Repo\PaymentRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$records = $this->equipmentLogRepository->getFinishedUnbilledRecords();
		foreach ($records as $record)
		{
			//How much does this device cost per hour
			$fee = \BB\Services\Credit::getDeviceFee($record->device);

			//How much does it cost per second
			$feePerSecond = $fee / (60*60);

			//How may seconds was the device in use
			$secondsActive = $record->finished->diffInSeconds($record->started);

			//How much did this session cost
			$incuredFee = (double)round(($feePerSecond * $secondsActive), 2);

			//Create a payment against the user
			$this->paymentRepository->recordPayment('equipment-fee', $record->user_id, 'balance', '', $incuredFee, 'paid', 0, $record->id.':'.$record->device);

			//Mark this log as being billed and complete
			$record->billed = true;
			$record->save();
		}
	}


}
