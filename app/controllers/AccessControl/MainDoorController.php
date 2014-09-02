<?php namespace AccessControl;

class MainDoorController extends \AccessControlController {

    public function all()
    {
        $page = \Input::get('page', 1);
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        $keyFobs = \KeyFob::active()->offset($offset)->take($perPage)->get();
        $responseArray = [];
        foreach ($keyFobs as $fob)
        {
            if ($fob->user()->first()->active) {
                $responseArray[] = $fob->key_id;
            }
        }
        return \Response::make(json_encode(['tags'=>$responseArray]), 200);
    }
} 