<?php
$I = new ApiTester($scenario);
$I->am('not a member with a random tag');
$I->wantTo('post to the legacy endpoint and confirm it fails');

//Send a bad code to the endpoint
$I->sendPOST('/access-control/legacy', ['data'=>'BADKEYFOB000:00:00']);

//The legacy endpoint always returns 200
$I->canSeeResponseCodeIs(200);

//Make sure a failure is returned
$I->canSeeResponseEquals('NOTFOUND');