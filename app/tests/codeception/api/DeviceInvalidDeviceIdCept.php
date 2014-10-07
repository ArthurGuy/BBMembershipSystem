<?php
$I = new ApiTester($scenario);
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