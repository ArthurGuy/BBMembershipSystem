<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use BB\Entities\KeyFob;
use BB\Entities\User;

class FunctionalHelper extends \Codeception\Module
{

    public function createMember()
    {
        return User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);
    }

    public function createActivity()
    {

    }

    public function loginNormalMember()
    {
        $user = User::find(1);
        \Auth::login($user);
        return $user;
    }

    public function loginAdminMember()
    {
        $user = User::find(3);
        \Auth::login($user);
        return $user;
    }

    public function loginLeftMember()
    {
        $user = User::find(2);
        \Auth::login($user);
        return $user;
    }

    public function loginLaserTeamMember()
    {
        $user = User::find(4);
        \Auth::login($user);
        return $user;
    }

    public function loginEquipmentTeamMember()
    {
        $user = User::find(4);
        \Auth::login($user);
        return $user;
    }

    public function getActiveKeyholderMember()
    {
        //We know this is user 1 in the DB
        return User::findOrFail(1);
    }

    public function getInactiveKeyholderMember()
    {
        return User::findOrFail(2);
    }


    public function getMemberKeyFob($userId)
    {
        return KeyFob::where('user_id', $userId)->first();
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
            return false;
        }
    }
}