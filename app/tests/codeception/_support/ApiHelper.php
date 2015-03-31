<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ApiHelper extends \Codeception\Module
{

    public function getActiveKeyholderMember()
    {
        //We know this is user 1 in the DB
        return \User::findOrFail(1);
    }

    public function getInactiveKeyholderMember()
    {
        return \User::findOrFail(2);
    }


    public function getMemberKeyFob($userId)
    {
        return \KeyFob::where('user_id', $userId)->first()->key_id;
    }

    public function seeExceptionThrown($exception, $function)
    {
        try
        {
            $function();
            return false;
        } catch (\Exception $e) {
            if( get_class($e) == $exception ){
                return true;
            }
            echo get_class($e);
            return false;
        }
    }
}