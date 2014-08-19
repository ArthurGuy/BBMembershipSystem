<?php

class KeyFobController extends \BaseController {


    /**
     * @var BB\Forms\KeyFob
     */
    private $keyFobForm;

    public function __construct(\BB\Forms\KeyFob $keyFobForm)
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
        $input = Input::only('user_id', 'key_id');

        try
        {
            $this->keyFobForm->validate($input);
        }
        catch (\BB\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        KeyFob::create($input);
        return Redirect::route('account.show', $input['user_id'])->withSuccess("Fob activated");
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
        return Redirect::route('account.show',$fob->user_id)->withSuccess("Fob marked lost");
	}


}
