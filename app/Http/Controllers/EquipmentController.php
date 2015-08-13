<?php namespace BB\Http\Controllers;

use BB\Exceptions\ImageFailedException;
use BB\Repo\EquipmentLogRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;
use BB\Repo\UserRepository;
use BB\Validators\EquipmentValidator;
use Illuminate\Support\Facades\Storage;

class EquipmentController extends Controller
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
        $this->middleware('role:equipment', array('except' => ['index', 'show']));
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

        return \View::make('equipment.index')->with('requiresInduction', $requiresInduction)->with('doesntRequireInduction', $doesntRequireInduction);
    }

    public function show($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);

        $trainers  = $this->inductionRepository->getTrainersForEquipment($equipment->induction_category);

        $equipmentLog = $this->equipmentLogRepository->getFinishedForEquipment($equipment->device_key);

        $usageTimes = [];
        $usageTimes['billed'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, true);
        $usageTimes['unbilled'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, false);
        $usageTimes['training'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, null, 'training');
        $usageTimes['testing'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, null, 'testing');

        $userInduction = $this->inductionRepository->getUserForEquipment(\Auth::user()->id, $equipment->induction_category);

        $trainedUsers = $this->inductionRepository->getTrainedUsersForEquipment($equipment->induction_category);

        $usersPendingInduction = $this->inductionRepository->getUsersPendingInductionForEquipment($equipment->induction_category);

        return \View::make('equipment.show')
            ->with('equipmentId', $equipmentId)
            ->with('equipment', $equipment)
            ->with('trainers', $trainers)
            ->with('equipmentLog', $equipmentLog)
            ->with('userInduction', $userInduction)
            ->with('trainedUsers', $trainedUsers)
            ->with('usersPendingInduction', $usersPendingInduction)
            ->with('usageTimes', $usageTimes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $memberList = $this->userRepository->getAllAsDropdown();
        $roleList = \BB\Entities\Role::lists('title', 'id');

        return \View::make('equipment.create')->with('memberList', $memberList)->with('roleList', $roleList->toArray());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ImageFailedException
     * @throws \BB\Exceptions\FormValidationException
     */
    public function store()
    {
        $data = \Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail', 'key',
            'device_key', 'description', 'help_text', 'managing_role_id', 'requires_induction', 'working', 'usage_cost', 'usage_cost_per',
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category', 'asset_tag_id',
        ]);
        $this->equipmentValidator->validate($data);

        $this->equipmentRepository->create($data);

        return \Redirect::route('equipment.edit', $data['key']);
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
        $roleList = \BB\Entities\Role::lists('title', 'id');
        //$roleList->prepend(null);
        //dd($roleList);

        return \View::make('equipment.edit')->with('equipment', $equipment)->with('memberList', $memberList)->with('roleList', $roleList->toArray());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  string $equipmentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($equipmentId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);

        $data = \Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail',
            'device_key', 'description', 'help_text', 'managing_role_id', 'requires_induction', 'working', 'usage_cost', 'usage_cost_per',
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category', 'asset_tag_id',
        ]);
        $this->equipmentValidator->validate($data, $equipment->id);

        $this->equipmentRepository->update($equipment->id, $data);

        return \Redirect::route('equipment.show', $equipmentId);
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

        $data = \Request::only(['photo']);

        $this->equipmentPhotoValidator->validate($data);

        if (\Input::file('photo')) {
            try {
                $filePath = \Input::file('photo')->getRealPath();
                $ext = \Input::file('photo')->guessClientExtension();
                $mimeType = \Input::file('photo')->getMimeType();
                $fileData = \Image::make($filePath)->fit(1000)->encode($ext);

                $newFilename = str_random() . '.' . $ext;

                Storage::put($equipment->getPhotoBasePath() . $newFilename, (string)$fileData, 'public');

                $equipment->addPhoto($newFilename);

            } catch(\Exception $e) {
                \Log::error($e);
                throw new ImageFailedException($e->getMessage());
            }
        }

        \Notification::success("Image added");
        return \Redirect::route('equipment.edit', $equipmentId);
    }

    public function destroyPhoto($equipmentId, $photoId)
    {
        $equipment = $this->equipmentRepository->findByKey($equipmentId);
        $photo = $equipment->photos[$photoId];
        $equipment->removePhoto($photoId);

        Storage::delete($equipment->getPhotoBasePath() . $photo['path']);

        \Notification::success("Image deleted");
        return \Redirect::route('equipment.edit', $equipmentId);
    }
} 