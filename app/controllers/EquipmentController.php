<?php

use BB\Repo\EquipmentLogRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;
use BB\Repo\UserRepository;
use BB\Validators\EquipmentValidator;

class EquipmentController extends \BaseController
{

    /**
     * @var InductionRepository
     */
    private $inductionRepository;
    /**
     * @var EquipmentRepository
     */
    private $equipmentRepository;
    /**
     * @var EquipmentLogRepository
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
     * @var EquipmentValidator
     */
    private $equipmentValidator;

    /**
     * @param InductionRepository      $inductionRepository
     * @param EquipmentRepository      $equipmentRepository
     * @param EquipmentLogRepository   $equipmentLogRepository
     * @param UserRepository                    $userRepository
     * @param EquipmentValidator $equipmentValidator
     */
    function __construct(
        InductionRepository $inductionRepository,
        EquipmentRepository $equipmentRepository,
        EquipmentLogRepository $equipmentLogRepository,
        UserRepository $userRepository,
        EquipmentValidator $equipmentValidator
    ) {
        $this->inductionRepository    = $inductionRepository;
        $this->equipmentRepository    = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->userRepository         = $userRepository;
        $this->equipmentValidator = $equipmentValidator;

        //Only members of the equipment group can create/update records
        $this->beforeFilter('role:equipment', array('except' => ['index', 'show']));
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

} 