<?php

namespace BB\Http\Controllers\ACS;

use BB\Entities\KeyFob;
use BB\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     *
     * @SWG\Get(
     *     path="/acs/status/{tagId}",
     *     tags={"acs"},
     *     description="Get information about a specific tag and its user",
     *     consumes={"application/json"},
     *     @SWG\Parameter(name="tagId", in="path", type="string"),
     *     @SWG\Response(response="200", description="Tag found"),
     *     @SWG\Response(response="404", description="Tag not found"),
     *     security={{"api_key": {}}}
     * )
     *
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

                //The ids coming in will have no checksum (last 2 digits) and the first digit will be incorrect

                //Remove the first character
                $tagId = substr($tagId, 1);

                try {
                    $keyFob = KeyFob::lookupPartialTag($tagId);
                } catch (\Exception $e) {
                    throw new ModelNotFoundException('Key fob ID not found');
                }
            }
        }

        return ['user' => [
            'id' => $keyFob->user->id,
            'name' => $keyFob->user->name,
            'status' => $keyFob->user->status,
            'active' => $keyFob->user->active,
            'key_holder' => $keyFob->user->key_holder,
            'cash_balance' => $keyFob->user->cash_balance,
            'profile_private' => $keyFob->user->profile_private,
        ]];
    }

}
