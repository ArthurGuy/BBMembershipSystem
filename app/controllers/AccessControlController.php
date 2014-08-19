<?php

class AccessControlController extends Controller
{

    public function mainDoor($keyId)
    {
        try {
            $keyFob = KeyFob::lookup($keyId);
        } catch (Exception $e) {
            return Response::make('Key not found', 404);
        }
        $user = $keyFob->user()->first();
        if ($user->active && $user->key_holder) {
            //OK
            return Response::make($user->name, 200);
        } elseif ($user->active) {
            //Not a keyholder
            return Response::make('Not a keyholder', 403);    //403 = forbidden
        } else {
            //bad
            return Response::make('Not active', 402);    //402 = payment required
        }
    }
} 