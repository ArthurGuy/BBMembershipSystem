<?php namespace BB\Http\Controllers;

use BB\Domain\Infrastructure\Device;
use BB\Domain\Infrastructure\DeviceRepository;
use BB\Domain\Infrastructure\Room;
use BB\Exceptions\ImageFailedException;
use BB\Repo\EquipmentLogRepository;
use BB\Repo\EquipmentRepository;
use BB\Repo\InductionRepository;
use BB\Domain\Infrastructure\RoomRepository;
use BB\Repo\UserRepository;
use BB\Validators\EquipmentPhotoValidator;
use BB\Validators\EquipmentValidator;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
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
     * @var EquipmentPhotoValidator
     */
    private $equipmentPhotoValidator;
    /**
     * @var RoomRepository
     */
    private $roomRepository;

    /**
     * @param InductionRepository     $inductionRepository
     * @param EquipmentRepository     $equipmentRepository
     * @param EquipmentLogRepository  $equipmentLogRepository
     * @param UserRepository          $userRepository
     * @param EquipmentValidator      $equipmentValidator
     * @param EquipmentPhotoValidator $equipmentPhotoValidator
     * @param RoomRepository          $roomRepository
     */
    function __construct(
        InductionRepository $inductionRepository,
        EquipmentRepository $equipmentRepository,
        EquipmentLogRepository $equipmentLogRepository,
        UserRepository $userRepository,
        EquipmentValidator $equipmentValidator,
        EquipmentPhotoValidator $equipmentPhotoValidator,
        RoomRepository $roomRepository
    ) {
        $this->inductionRepository    = $inductionRepository;
        $this->equipmentRepository    = $equipmentRepository;
        $this->equipmentLogRepository = $equipmentLogRepository;
        $this->userRepository         = $userRepository;
        $this->equipmentValidator = $equipmentValidator;
        $this->equipmentPhotoValidator = $equipmentPhotoValidator;
        $this->roomRepository          = $roomRepository;

        //Only members of the equipment group can create/update records
        $this->middleware('role:equipment', array('except' => ['index', 'show']));

        $this->ppeList = [
            'eye-protection' => 'Eye protection',
            'gloves'         => 'Gloves',
            'face-guard'     => 'Full face guard',
            'face-mask'      => 'Face mask',
            'welding-mask'   => 'Welding mask',
            'ear-protection' => 'Ear protection'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(DeviceRepository $deviceRepository)
    {
        $devices = $deviceRepository->findAll();

        //filter the devices into two lists
        $requiresInduction = array_filter($devices, function($d) {
            if ($d->getCost()->getRequiresInduction()) {
                return true;
            }
            return false;
        });
        $doesntRequireInduction = array_filter($devices, function($d) {
            if ( ! $d->getCost()->getRequiresInduction()) {
                return true;
            }
            return false;
        });

        $rooms = [];
        $rooms = $this->roomRepository->findAll();

        return \View::make('equipment.index')
            ->with('requiresInduction', $requiresInduction)
            ->with('doesntRequireInduction', $doesntRequireInduction)
            ->with('rooms', $rooms);
    }

    public function show($equipmentId)
    {
        $equipment = $this->equipmentRepository->findBySlug($equipmentId);

        $trainers  = $this->inductionRepository->getTrainersForEquipment($equipment->induction_category);

        $equipmentLog = $this->equipmentLogRepository->getFinishedForEquipment($equipment->device_key);

        $usageTimes = [];
        $usageTimes['billed'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, true, '');
        $usageTimes['unbilled'] = $this->equipmentLogRepository->getTotalTime($equipment->device_key, false, '');
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

        //$room = new Room('main', 'Main Space', '', []);
        //$this->roomRepository->add($room);
        //$em->flush();

        $memberList = $this->userRepository->getAllAsDropdown();
        $roleList = \BB\Entities\Role::lists('title', 'id');

        return \View::make('equipment.create')->with('memberList', $memberList)->with('roleList', $roleList->toArray())->with('ppeList', $this->ppeList);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws ImageFailedException
     * @throws \BB\Exceptions\FormValidationException
     */
    public function store(Request $request, DeviceRepository $deviceRepository, EntityManager $em)
    {
        $data = \Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail', 'slug',
            'device_key', 'description', 'help_text', 'managing_role_id', 'requires_induction', 'working', 'usage_cost', 'usage_cost_per',
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category', 'asset_tag_id', 'ppe',
        ]);//24
        //$this->equipmentValidator->validate($data);

        //$this->equipmentRepository->create($data);


        /** @var \Illuminate\Http\Request $request */

        $device = new Device($request->get('name'), $request->get('key'));
        $deviceRepository->add($device);
        $em->flush();

/*
        $cost = new EquipmentCost($request->get('requires_induction'), $request->get('induction_category'), $request->get('access_fee'), $request->get('usage_cost'), $request->get('usage_cost_per'));
        $properties = new EquipmentProperties($request->get('manufacturer'), $request->get('model_number'), $request->get('serial_number'), $request->get('colour'));
        $ownership = new Ownership($request->get('managing_role_id'), $request->get('permaloan'), $request->get('permaloan_user_id'));
        $device = new Device($request->get('name'), $request->get('key'), $request->get('description'), $request->get('help_text'), $properties, $cost, $ownership, $request->get('obtained_at'));



        $cost = new EquipmentCost($request->get('requires_induction'), $request->get('induction_category'), $request->get('access_fee'), $request->get('usage_cost'), $request->get('usage_cost_per'));
        $device = new Device($request->get('name'), $request->get('key'));
        $device->setDeviceCost($cost);
        $device->setDescription($request->get('description'));
        $device->setHelpText($request->get('help_text'));
        $device->setManufacturer($request->get('manufacturer'));
        $device->setModelNumber($request->get('model_number'));
        $device->setSerialNumber($request->get('serial_number'));
        $device->setAssetTagId($request->get('asset_tag_id'));
        $device->setColour($request->get('colour'));
        $device->setRoom($request->get('room'));
        $device->setDetail($request->get('detail'));
        $device->setWorking($request->get('working'));
        $device->setManagingRole($request->get('managing_role_id'));
        $device->setPpe($request->get('ppe'));
        $device->setPermaloan($request->get('permaloan'));
        $device->setPermaloanUser($request->get('permaloan_user_id'));
        $device->setDateObtained($request->get('obtained_at'));
        $device->setInductionCategory($request->get('induction_category'));




        $cost = new EquipmentCost($request->get('requires_induction'), $request->get('induction_category'), $request->get('access_fee'), $request->get('usage_cost'), $request->get('usage_cost_per'));
        $device = new Device($request->get('name'), $request->get('key'));
        $device->setDeviceCost($cost);
        $device->setProperties([
            'description'        => $request->get('description'),
            'help_text'          => $request->get('help_text'),
            'manufacturer'       => $request->get('manufacturer'),
            'model_number'       => $request->get('model_number'),
            'serial_number'      => $request->get('serial_number'),
            'asset_tag_id'       => $request->get('asset_tag_id'),
            'colour'             => $request->get('colour'),
            'room'               => $request->get('room'),
            'detail'             => $request->get('detail'),
            'working'            => $request->get('working'),
            'managing_role_id'   => $request->get('managing_role_id'),
            'ppe'                => $request->get('ppe'),
            'permaloan'          => $request->get('permaloan'),
            'permaloan_user_id'  => $request->get('permaloan_user_id'),
            'obtained_at'        => $request->get('obtained_at'),
            'induction_category' => $request->get('induction_category'),
        ]);

        */

        return \Redirect::route('equipment.edit', $data['slug']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $equipmentId
     * @return Response
     */
    public function edit($equipmentId)
    {
        $equipment = $this->equipmentRepository->findBySlug($equipmentId);
        $memberList = $this->userRepository->getAllAsDropdown();
        $roleList = \BB\Entities\Role::lists('title', 'id');
        //$roleList->prepend(null);
        //dd($roleList);

        return \View::make('equipment.edit')->with('equipment', $equipment)->with('memberList', $memberList)->with('roleList', $roleList->toArray())->with('ppeList', $this->ppeList);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  string $equipmentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($equipmentId)
    {
        $equipment = $this->equipmentRepository->findBySlug($equipmentId);

        $data = \Request::only([
            'name', 'manufacturer', 'model_number', 'serial_number', 'colour', 'room', 'detail',
            'device_key', 'description', 'help_text', 'managing_role_id', 'requires_induction', 'working', 'usage_cost', 'usage_cost_per',
            'permaloan', 'permaloan_user_id', 'access_fee', 'obtained_at', 'removed_at', 'induction_category', 'asset_tag_id', 'ppe',
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
        $equipment = $this->equipmentRepository->findBySlug($equipmentId);

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
        $equipment = $this->equipmentRepository->findBySlug($equipmentId);
        $photo = $equipment->photos[$photoId];
        $equipment->removePhoto($photoId);

        Storage::delete($equipment->getPhotoBasePath() . $photo['path']);

        \Notification::success("Image deleted");
        return \Redirect::route('equipment.edit', $equipmentId);
    }
} 