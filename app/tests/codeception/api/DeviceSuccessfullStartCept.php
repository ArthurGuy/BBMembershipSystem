<?php
$I = new ApiTester($scenario);
$I->am('a valid user');
$I->wantTo('verify the endpoint returns a success response');

$user = $I->getActiveKeyholderMember();
$keyFob = $I->getMemberKeyFob($user->id);

//Send a bad code to the endpoint
$I->sendPOST('/access-control/device', ['data'=>$keyFob->key_id.'|laser|start']);

//The device endpoint always returns 200
$I->canSeeResponseCodeIs(200);

//Make sure a success is returned and a session started
$I->canSeeResponseContainsJson(['valid'=>'1']);
$I->seeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'laser', 'active'=>1]);