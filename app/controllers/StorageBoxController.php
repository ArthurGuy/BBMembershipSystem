<?php

class StorageBoxController extends \BaseController {

    /**
     * @var \BB\Repo\StorageBoxRepository
     */
    private $storageBoxRepository;

    public function __construct(\BB\Repo\StorageBoxRepository $storageBoxRepository)
    {
        $this->storageBoxRepository = $storageBoxRepository;
    }


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $storageBoxes = $this->storageBoxRepository->getAll();

        $availableBoxes = $this->storageBoxRepository->numAvailableBoxes();

        $memberBox = $this->storageBoxRepository->getMemberBox(Auth::user()->id);

        $boxPayment = Auth::user()->getStorageBoxPayment();

        $canClaimBox = false;
        if (($availableBoxes > 0) && $boxPayment && !$memberBox) {
            $canClaimBox = true;
        }

        return View::make('storage_boxes.index')
            ->with('storageBoxes', $storageBoxes)
            ->with('memberBox', $memberBox)
            ->with('boxPayment', $boxPayment)
            ->with('availableBoxes', $availableBoxes)
            ->with('canClaimBox', $canClaimBox);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


    /**
     * Update the specified resource in storage.
     *
     * @param $boxId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\ValidationException
     * @internal param int $id
     * @return Response
     */
	public function update($boxId)
	{
        $userId = Request::get('user_id');

        if ($userId) {
            $this->selfClaimBox($boxId, $userId);
        } else {
            //No id - reclaiming the box
            if (!Auth::user()->isAdmin()) {
                throw new \BB\Exceptions\AuthenticationException();
            }

            $this->storageBoxRepository->update($boxId, ['user_id'=>0]);
        }

        Notification::success("Member box updated");
        return Redirect::route('storage_boxes.index');
	}

    private function selfClaimBox($boxId, $userId)
    {
        if ($userId != Auth::user()->id) {
            throw new \BB\Exceptions\AuthenticationException();
        }

        $box = $this->storageBoxRepository->getById($boxId);

        //Make sure the box is available
        if (!$box->available) {
            throw new \BB\Exceptions\ValidationException();
        }

        //Does the user have a box
        $memberBox = $this->storageBoxRepository->getMemberBox(Auth::user()->id);
        if ($memberBox) {
            throw new \BB\Exceptions\ValidationException();
        }

        //Have the paid for a box
        $boxPayment = Auth::user()->getStorageBoxPayment();
        if (!$boxPayment) {
            throw new \BB\Exceptions\ValidationException();
        }

        $this->storageBoxRepository->update($boxId, ['user_id'=>Auth::user()->id]);
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
