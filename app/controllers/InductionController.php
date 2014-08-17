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
        $this->layout->content = View::make('induction.index')->withInductions(Induction::all());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
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
        return Redirect::route('account.show', $userId)->withSuccess("Updated");
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
