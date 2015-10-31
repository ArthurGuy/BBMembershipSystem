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

                //Remove the first character
                $tagId = substr($tagId, 1);

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

}
