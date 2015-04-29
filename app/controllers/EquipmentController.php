<?php

use BB\Repo\UserRepository;

class EquipmentController extends \BaseController
{

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
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Validators\EquipmentValidator
     */
    private $equipmentValidator;

    /**
     * @param \BB\Repo\InductionRepository    $inductionRepository
     * @param \BB\Repo\EquipmentRepository    $equipmentRepository
     * @param \BB\Repo\EquipmentLogRepository $equipmentLogRepository
     */
    function __construct(
        \BB\Repo\InductionRepository $inductionRepository,
        \BB\Repo\EquipmentRepository $equipmentRepository,
        \BB\Repo\EquipmentLogRepository $equipmentLogRepository,
        UserRepository $userRepository,
        \BB\Validators\EquipmentValidator $equipmentValidator
    ) {
        $this->inductionRepository    = $inductionRepository;
        $this->equipmentRepository    = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->userRepository         = $userRepository;
        $this->equipmentValidator = $equipmentValidator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $requiresInduction = $this->equipmentRepository->getRequiresInduction();
        $doesntRequireInduction = $this->equipmentRepository->getDoesntRequireInduction();

        return View::make('equipment.index')->with('requiresInduction', $requiresInduction)->with('doesntRequireInduction', $doesntRequireInduction);
    }

    public function show($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);
        $trainers  = $this->inductionRepository->getTrainersForEquipment($equipmentId);

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