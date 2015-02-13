<?php 

class EquipmentController extends \BaseController {

    /**
     * @var \BB\Repo\InductionRepository
     */
    private $inductionRepository;
    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var \BB\Repo\EquipmentLogRepository
     */
    private $equipmentLogRepository;
    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    /**
     * @param \BB\Repo\InductionRepository    $inductionRepository
     * @param \BB\Repo\EquipmentRepository    $equipmentRepository
     * @param \BB\Repo\EquipmentLogRepository $equipmentLogRepository
     */
    function __construct(\BB\Repo\InductionRepository $inductionRepository, \BB\Repo\EquipmentRepository $equipmentRepository, \BB\Repo\EquipmentLogRepository $equipmentLogRepository, \BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->inductionRepository = $inductionRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $equipment = $this->equipmentRepository->all();
        $trainers = $this->inductionRepository->getTrainersByEquipment();

        $usersPendingInduction = $this->inductionRepository->getUsersPendingInduction();

        $trainedUsers = $this->inductionRepository->getTrainedUsers();

        $inductions  = Induction::orderBy('key')->get();

        return View::make('equipment.index')
            ->with('inductions', $inductions)
            ->with('trainers', $trainers)
            ->with('equipment', $equipment)
            ->with('usersPendingInduction', $usersPendingInduction)
            ->with('trainedUsers', $trainedUsers);
    }

    public function show($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);
        $trainers = $this->inductionRepository->getTrainersForEquipment($equipmentId);

        $equipmentLog = $this->equipmentLogRepository->getFinishedForEquipment($equipmentId);

        return View::make('equipment.show')->with('equipmentId', $equipmentId)->with('equipment', $equipment)->with('trainers', $trainers)->with('equipmentLog', $equipmentLog);
    }

    public function updateLogEntry($logEntryId)
    {
        $reason = Request::get('reason');

        if (!in_array($reason, ['training', 'testing'])) {
            throw new \BB\Exceptions\ValidationException("Not a valid reason");
        }

        $equipmentLog = $this->equipmentLogRepository->getById($logEntryId);

        if ($equipmentLog->user_id == Auth::user()->id) {
            throw new \BB\Exceptions\ValidationException("You can't update your own record");
        }

        if (!empty($equipmentLog->reason)) {
            throw new \BB\Exceptions\ValidationException("Reason already set");
        }

        $billedStatus = $equipmentLog->billed;

        if ($equipmentLog->billed) {
            //the user has been billed, we need to undo this.
            $payments = $this->paymentRepository->getPaymentsByReference($equipmentLog->id.':'.$equipmentLog->device);
            if ($payments->count() == 1) {
                $this->paymentRepository->delete($payments->first()->id);
                $billedStatus = false;
            } else {
                throw new \BB\Exceptions\ValidationException("Unable to locate related payment, please contact and admin");
            }
        }

        $this->equipmentLogRepository->update($logEntryId, ['reason'=>$reason, 'billed'=>$billedStatus]);

        //Notification::success("Record Updated");
        return Redirect::back();

    }


} 