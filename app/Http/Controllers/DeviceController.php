<?php

namespace BB\Http\Controllers;

use BB\Entities\ACSNode;
use BB\Http\Requests;

class DeviceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $devices = ACSNode::all();

        return view('devices.index', ['devices' => $devices]);
    }

    public function create()
    {
        return view('devices.create');
    }

    public function store()
    {
        $data = \Request::only([
            'name', 'device_id', 'api_key', 'entry_device',
        ]);

        ACSNode::create($data);

        return \Redirect::route('devices.index');
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
        $device = ACSNode::findOrFail($id);
        $device->delete();

        return \Redirect::route('devices.index');
    }
}
