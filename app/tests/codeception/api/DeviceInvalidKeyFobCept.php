<?php
$I = new ApiTester($scenario);
$I->am('an invalid user');
$I->wantTo('verify the endpoint returns a failure response');

//Send a bad code to the endpoint
$I->sendPOST('/access-control/device', ['data'=>'BADKEYFOB000|laser|start']);

//The device endpoint always returns 200
$I->canSeeResponseCodeIs(200);

//Make sure a failure is returned
$I->canSeeResponseContainsJson(['valid'=>'0']);