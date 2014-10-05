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
     * @param \BB\Repo\InductionRepository $inductionRepository
     */
    function __construct(\BB\Repo\InductionRepository $inductionRepository, \BB\Repo\EquipmentRepository $equipmentRepository)
    {
        $this->inductionRepository = $inductionRepository;
        $this->equipmentRepository = $equipmentRepository;
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
} 