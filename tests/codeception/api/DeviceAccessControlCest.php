<?php

class DeviceAccessControlCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function invalidDevice(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns a failure response');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Send a bad code to the endpoint
        $I->sendPOST('/access-control/device', ['data'=>$keyFob->key_id.'|bad-device|start']);

        //The device endpoint always returns 200
        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseContainsJson(['valid'=>'0']);
    }

    public function unknownUser(ApiTester $I)
    {
        $I->am('an invalid user');
        $I->wantTo('verify the endpoint returns a failure response');

        //Send a bad code to the endpoint
        $I->sendPOST('/access-control/device', ['data'=>'BADKEYFOB000|laser|start']);

        //The device endpoint always returns 200
        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseContainsJson(['valid'=>'0']);
    }

    public function successfullStart(ApiTester $I)
    {
        $I->am('a valid user');
        $I->wantTo('verify the endpoint returns a success response');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Send a bad code to the endpoint
        $I->sendPOST('/access-control/device', ['data'=>$keyFob->key_id.'|laser|start']);

        //The device endpoint always returns 200
        $I->canSeeResponseCodeIs(200);

        $I->dontSeeHttpHeader('Set-Cookie');
        $I->dontSeeHttpHeader('Built-By');

        //Make sure a success is returned and a session started
        $I->canSeeResponseContainsJson(['valid'=>'1']);
        $I->seeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'laser', 'active'=>1]);
    }

    public function successfullEnd(ApiTester $I)
    {
        $I->am('a valid user');
        $I->wantTo('verify the endpoint returns a success response and creates the proper records');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Send a bad code to the endpoint
        $I->sendPOST('/access-control/device', ['data'=>$keyFob->key_id.'|welder|start']);

        //The device endpoint always returns 200
        $I->canSeeResponseCodeIs(200);

        //Make sure a success is returned and a session started
        $I->canSeeResponseContainsJson(['valid'=>'1']);
        $I->seeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'welder', 'active'=>1]);


        $I->sendPOST('/access-control/device', ['data'=>$keyFob->key_id.'|welder|end']);
        $I->canSeeResponseCodeIs(200);

        $I->dontSeeHttpHeader('Set-Cookie');
        $I->dontSeeHttpHeader('Built-By');

        $I->canSeeResponseContainsJson(['valid'=>'1']);

        //Make sure our database record is not active
        $I->seeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'welder', 'active'=>0]);
        //And make sure there is no other active record
        $I->cantSeeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'welder', 'active'=>1]);
    }

}