<?php

namespace BB\Http\Controllers\ACS;

use BB\Entities\KeyFob;
use BB\Exceptions\ValidationException;
use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  string $tagId
     *
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function show($tagId)
    {
        try {
            $keyFob = KeyFob::lookup($tagId);
        } catch (\Exception $e) {
            $oldTagId = substr('BB' . $tagId, 0, 12);
            try {
                $keyFob = KeyFob::lookup($oldTagId);
            } catch (\Exception $e) {

                $fobs = KeyFob::where('key_id', 'LIKE', '%' . $tagId . '%')->get();
                if ($fobs->count() == 1) {
                    $keyFob = $fobs->first();
                } else {
                    throw new ValidationException('Key fob ID not valid');
                }
            }
        }

        return $keyFob;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
