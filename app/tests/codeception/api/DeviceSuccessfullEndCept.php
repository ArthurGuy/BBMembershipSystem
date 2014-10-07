<?php
$I = new ApiTester($scenario);
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
$I->canSeeResponseContainsJson(['valid'=>'1']);

//Make sure our database record is not active
$I->seeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'welder', 'active'=>0]);
//And make sure there is no other active record
$I->cantSeeInDatabase('equipment_log', ['user_id'=>$user->id, 'device'=>'welder', 'active'=>1]);