<?php

class AccessControlController extends Controller
{

    public function mainDoor()
    {
        $keyId = Input::get('data');
        try {
            $keyFob = KeyFob::lookup($keyId);
        } catch (Exception $e) {
            return Response::make('Key not found', 404);
        }
        $user = $keyFob->user()->first();

        $log = new AccessLog();
        $log->key_fob_id = $keyFob->id;
        $log->user_id = $user->id;
        $log->service = 'main-door';

        if ($user->active && $user->key_holder) {
            //OK
            $log->response = 200;
            $log->save();
            return Response::make($user->name, 200);
        } elseif ($user->active) {
            //Not a keyholder
            $log->response = 403;
            $log->save();
            return Response::make('Not a keyholder', 403);    //403 = forbidden
        } else {
            //bad
            $log->response = 402;
            $log->save();
            return Response::make('Not active', 402);    //402 = payment required
        }
    }
} 