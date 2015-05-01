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
     * @var \BB\Validators\EquipmentPhotoValidator
     */
    private $equipmentPhotoValidator;

    /**
     * @param InductionRepository                    $inductionRepository
     * @param EquipmentRepository                    $equipmentRepository
     * @param EquipmentLogRepository                 $equipmentLogRepository
     * @param UserRepository                         $userRepository
     * @param EquipmentValidator                     $equipmentValidator
     * @param \BB\Validators\EquipmentPhotoValidator $equipmentPhotoValidator
     */
    function __construct(
        InductionRepository $inductionRepository,
        EquipmentRepository $equipmentRepository,
        EquipmentLogRepository $equipmentLogRepository,
        UserRepository $userRepository,
        EquipmentValidator $equipmentValidator,
        \BB\Validators\EquipmentPhotoValidator $equipmentPhotoValidator
    ) {
        $this->inductionRepository    = $inductionRepository;
        $this->equipmentRepository    = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->userRepository         = $userRepository;
        $this->equipmentValidator = $equipmentValidator;
        $this->equipmentPhotoValidator = $equipmentPhotoValidator;

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
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category',
        ]);
        $this->equipmentValidator->validate($data);

        $this->equipmentRepository->create($data);

        return Redirect::route('equipment.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $equipmentId
     * @return Response
     */
    public function edit($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);
        $memberList = $this->userRepository->getAllAsDropdown();

        return View::make('equipment.edit')->with('equipment', $equipment)->with('memberList', $memberList);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  string $equipmentId
     * @return Response
     */
    public function update($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);

        $data = Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail',
            'device_key', 'description', 'help_text', 'owner_role_id', 'requires_induction', 'working',
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category',
        ]);
        $this->equipmentValidator->validate($data, $equipment->id);

        $this->equipmentRepository->update($equipment->id, $data);

        return Redirect::route('equipment.show', $equipmentId);
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

    public function addPhoto($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);

        $data = Request::only(['photo']);

        $this->equipmentPhotoValidator->validate($data);

        if (Input::file('photo'))
        {
            $filePath = Input::file('photo')->getRealPath();
            $tmpFilePath = storage_path("tmp")."/equipment/".$equipment->id.".png";
            Image::make($filePath)->fit(1000)->save($tmpFilePath);

            $newFilename = $equipment->getPhotoPath($equipment->photos + 1);

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

                $equipment->photos = $equipment->photos + 1;
                $equipment->save();

            } catch(\Exception $e) {
                \Log::exception($e);
                throw new ImageFailedException();
            }
        }

        return Redirect::route('equipment.show', $equipmentId);
    }
} 