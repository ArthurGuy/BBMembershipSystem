<?php namespace AccessControl;

class MainDoorController extends \AccessControlController {

    public function all()
    {
        $keyFobs = \KeyFob::active()->get();
        $responseArray = [];
        foreach ($keyFobs as $fob)
        {
            $responseArray[] = $fob->key_id;
        }
        return \Response::make(json_encode($responseArray), 200);
    }
} 