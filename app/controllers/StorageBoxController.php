<?php

class StorageBoxController extends \BaseController {


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $storageBoxes = StorageBox::all();

        $availableBoxes = 0;
        foreach ($storageBoxes as $box) {
            if (empty($box->user_id)) {
                $availableBoxes++;
            }
        }
        $memberBox = StorageBox::findMember(Auth::user()->id);

        $boxPayment = Auth::user()->getStorageBoxPayment();

        return View::make('storage_boxes.index')
            ->with('storageBoxes', $storageBoxes)
            ->with('memberBox', $memberBox)
            ->with('boxPayment', $boxPayment)
            ->with('availableBoxes', $availableBoxes);
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
		//
	}


}
