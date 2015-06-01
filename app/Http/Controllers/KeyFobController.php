<?php namespace BB\Http\Controllers;

use BB\Entities\KeyFob;

class KeyFobController extends Controller
{


    /**
     * @var \BB\Validators\KeyFob
     */
    private $keyFobForm;

    public function __construct(\BB\Validators\KeyFob $keyFobForm)
    {

        $this->keyFobForm = $keyFobForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
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
        $input = \Input::only('user_id', 'key_id');

        $this->keyFobForm->validate($input);

        KeyFob::create($input);

        \Notification::success("Key Fob Activated");
        return \Redirect::route('account.show', $input['user_id']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $fob = KeyFob::findOrFail($id);
        $fob->markLost();
        \Notification::success("Key Fob marked as lost/broken");
        return \Redirect::route('account.show',$fob->user_id);
    }


}
