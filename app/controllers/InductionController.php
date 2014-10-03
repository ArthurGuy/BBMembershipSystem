<?php

class InductionController extends \BaseController
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

        $inductions  = Induction::orderBy('key')->get();

        return View::make('induction.index')
            ->with('inductions', $inductions)
            ->with('trainers', $trainers)
            ->with('equipment', $equipment)
            ->with('usersPendingInduction', $usersPendingInduction);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param      $userId
     * @param  int $id
     * @throws BB\Exceptions\NotImplementedException
     * @return Response
     */
    public function update($userId, $id)
    {
        $induction = Induction::findOrFail($id);

        if (Input::get('mark_trained', false)) {
            $induction->trained = \Carbon\Carbon::now();
            $induction->trainer_user_id = Input::get('trainer_user_id', false);
            $induction->save();
        } elseif (Input::get('is_trainer', false)) {
            $induction->is_trainer = true;
            $induction->save();
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
        Notification::success("Updated");
        return Redirect::route('account.show', $userId);
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
