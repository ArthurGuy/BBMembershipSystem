<?php 

class EquipmentLogController extends \BaseController
{
    /**
     * @var \BB\Repo\EquipmentLogRepository
     */
    private $equipmentLogRepository;
    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param \BB\Repo\EquipmentLogRepository $equipmentLogRepository
     * @param \BB\Repo\PaymentRepository      $paymentRepository
     */
    function __construct(\BB\Repo\EquipmentLogRepository $equipmentLogRepository, \BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->paymentRepository = $paymentRepository;
    }


    public function update($logEntryId)
    {
        $reason = Request::get('reason');

        if ( ! in_array($reason, ['training', 'testing'])) {
            throw new \BB\Exceptions\ValidationException("Not a valid reason");
        }

        $equipmentLog = $this->equipmentLogRepository->getById($logEntryId);

        if ($equipmentLog->user_id == Auth::user()->id) {
            throw new \BB\Exceptions\ValidationException("You can't update your own record");
        }

        if ( ! Auth::user()->hasRole($equipmentLog->device) && ! Auth::user()->isAdmin()) {
            throw new \BB\Exceptions\ValidationException("You don't have permission to alter this record");
        }

        if ( ! empty($equipmentLog->reason)) {
            throw new \BB\Exceptions\ValidationException("Reason already set");
        }

        $billedStatus = $equipmentLog->billed;

        if ($equipmentLog->billed) {
            //the user has been billed, we need to undo this.
            $payments = $this->paymentRepository->getPaymentsByReference($equipmentLog->id . ':' . $equipmentLog->device);
            if ($payments->count() == 1) {
                $this->paymentRepository->delete($payments->first()->id);
                $billedStatus = false;
            } else {
                throw new \BB\Exceptions\ValidationException("Unable to locate related payment, please contact an admin");
            }
        }

        $this->equipmentLogRepository->update($logEntryId, ['reason'=>$reason, 'billed'=>$billedStatus]);

        Notification::success("Record Updated");
        return Redirect::back();

    }


} 