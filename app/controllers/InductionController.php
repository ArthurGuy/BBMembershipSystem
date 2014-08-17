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

        $trained = Input::get('mark_trained', false);
        if ($trained) {
            $induction->trained = \Carbon\Carbon::now();
            $induction->trainer_user_id = Input::get('trainer_user_id', false);
            $induction->save();
            return Redirect::route('account.show', $userId)->withSuccess("Updated");
        } else {
            throw new \BB\Exceptions\NotImplementedException();
        }
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
