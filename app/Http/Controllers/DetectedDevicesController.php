<?php

namespace BB\Http\Controllers;

use BB\Entities\DetectedDevice;
use Illuminate\Http\Request;

use BB\Http\Requests;
use BB\Http\Controllers\Controller;

class DetectedDevicesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $devices = DetectedDevice::orderBy('created_at', 'desc')->get();
        $uniqueDevices = $devices->unique('mac_address');

        //Attach the count of the number of occurrences to each record
        $uniqueDevices->each(function ($item, $key) use ($devices) {

            $item['occurrences'] = $devices->where('mac_address', $item['mac_address'])->count();

        });

        //Ensure the display name is always present and doesn't get overridden by a newer blank name
        $uniqueDevices->each(function ($item, $key) use ($devices) {

            $item['display_name'] = $devices->where('mac_address', $item['mac_address'])->sortByDesc('display_name')->first()->display_name;

        });

        return view('detected_devices.index', ['devices' => $uniqueDevices]);
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
