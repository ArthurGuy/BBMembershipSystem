<?php

use BB\Exceptions\ImageFailedException;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $memberList = $this->userRepository->getAllAsDropdown();

        return View::make('equipment.create')->with('memberList', $memberList);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     * @throws ImageFailedException
     * @throws \BB\Exceptions\FormValidationException
     */
    public function store()
    {
        $data = Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail', 'key',
            'device_key', 'description', 'help_text', 'owner_role_id', 'requires_induction', 'working',
            'permaloan', 'permaloan_user_id', 'access_fee', 'photo', 'obtained_at', 'removed_at',
        ]);
        $this->equipmentValidator->validate($data);

        $equipment = $this->equipmentRepository->create($data);

        if (Input::file('photo'))
        {
            $filePath = Input::file('photo')->getRealPath();
            $tmpFilePath = storage_path("tmp")."/equipment/".$equipment->id.".png";
            Image::make($filePath)->fit(1000)->save($tmpFilePath);

            $newFilename = $equipment->getPhotoPath(1);

            $s3 = \AWS::get('s3');
            try {
                $s3->putObject(array(
                    'Bucket'        => getenv('S3_BUCKET'),
                    'Key'           => $newFilename,
                    'Body'          => file_get_contents($tmpFilePath),
                    'ACL'           => 'public-read',
                    'ContentType'   => 'image/png',
                    'ServerSideEncryption' => 'AES256',
                ));
                File::delete($tmpFilePath);

                $equipment->photos = 1;
                $equipment->save();

            } catch(\Exception $e) {
                \Log::exception($e);
                throw new ImageFailedException();
            }
        }

        return Redirect::route('equipment.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
} 