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
    function __construct(\BB\Repo\InductionRepository $inductionRepository, \BB\Repo\EquipmentRepository $equipmentRepository, \BB\Repo\EquipmentLogRepository $equipmentLogRepository)
    {
        $this->inductionRepository = $inductionRepository;
        $this->equipmentRepository = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $equipment = $this->equipmentRepository->all();

        return View::make('equipment.index')->with('equipment', $equipment);
    }

    public function show($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);
        $trainers = $this->inductionRepository->getTrainersForEquipment($equipmentId);

        $equipmentLog = $this->equipmentLogRepository->getFinishedForEquipment($equipmentId);

        $userInduction = $this->inductionRepository->getUserForEquipment(Auth::user()->id, $equipmentId);

        $trainedUsers = $this->inductionRepository->getTrainedUsersForEquipment($equipmentId);

        $usersPendingInduction = $this->inductionRepository->getUsersPendingInductionForEquipment($equipmentId);

        return View::make('equipment.show')
                        ->with('equipmentId', $equipmentId)
                        ->with('equipment', $equipment)
                        ->with('trainers', $trainers)
                        ->with('equipmentLog', $equipmentLog)
                        ->with('userInduction', $userInduction)
                        ->with('trainedUsers', $trainedUsers)
                        ->with('usersPendingInduction', $usersPendingInduction);
    }

} 