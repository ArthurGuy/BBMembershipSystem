<?php namespace BB\Services;


class EquipmentCharge
{

    /**
     * @var \BB\Repo\EquipmentLogRepository
     */
    protected $equipmentLogRepository;

    /**
     * @var \BB\Repo\PaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var \BB\Repo\EquipmentRepository
     */
    protected $equipmentRepository;

    public function __construct()
    {
        $this->equipmentLogRepository = \App::make('\BB\Repo\EquipmentLogRepository');
        $this->paymentRepository = \App::make('\BB\Repo\PaymentRepository');
        $this->equipmentRepository = \App::make('\BB\Repo\EquipmentRepository');
    }

    public function calculatePendingFees()
    {
        $records = $this->equipmentLogRepository->getFinishedUnbilledRecords();
        foreach ($records as $record) {

            $equipment = $this->equipmentRepository->findBySlug($record->device);
            if ($equipment->hasUsageCharge()) {

                $feePerSecond = $this->costPerSecond($equipment->usageCost);

                //How may seconds was the device in use
                $secondsActive = $record->finished->diffInSeconds($record->started);

                //Charges are for a minimum of 15 minutes
                $secondsActive = $this->roundUpSecondsActive($secondsActive);

                $incurredFee = $this->sessionFee($feePerSecond, $secondsActive);

                //If the reason is empty then its not a special case and should be billed
                if (empty($record->reason)) {
                    //Create a payment against the user
                    $this->paymentRepository->recordPayment('equipment-fee', $record->user_id, 'balance', '',
                        $incurredFee, 'paid', 0, $record->id . ':' . $record->device);
                }

            }

            //Mark this log as being billed and complete
            $record->billed = true;
            $record->save();
        }
    }

    /**
     * Ensure the seconds are at least 900 (15 minutes)
     * 
     * @param $seconds int
     * @return int
     */
    private function roundUpSecondsActive($seconds)
    {
        if ($seconds < 900) {
            $seconds = 900;
        }
        return $seconds;
    }

    /**
     * @param $feePerHour
     * @return float
     */
    private function costPerSecond($feePerHour)
    {
        return $feePerHour / (60 * 60);
    }

    /**
     * @param $feePerSecond
     * @param $secondsActive
     * @return float
     */
    private function sessionFee($feePerSecond, $secondsActive)
    {
        return (double)round(($feePerSecond * $secondsActive), 2);
    }

}
