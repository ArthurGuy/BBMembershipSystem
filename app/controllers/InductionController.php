<?php

class InductionController extends \BaseController
{


    protected $layout = 'layouts.main';

    function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $inductions  = Induction::all();
        $trainersRaw = Induction::where('is_trainer', true)->get();
        $inductionList = Induction::inductionList();
        $trainers = [];
        foreach ($inductionList as $equipmentKey => $equipment)
        {
            $trainers[$equipmentKey] = [];
        }
        foreach ($trainersRaw as $trainer) {
            if (isset($trainer->user->name))
            {
                $trainers[$trainer->key][] = $trainer->user->name;
            }
        }

        $this->layout->content = View::make('induction.index')->with('inductions', $inductions)->with('trainers', $trainers)->with('inductionList', $inductionList);
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
