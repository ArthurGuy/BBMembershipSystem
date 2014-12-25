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
} 