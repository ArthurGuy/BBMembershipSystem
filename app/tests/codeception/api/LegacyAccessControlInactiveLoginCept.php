<?php
$I = new ApiTester($scenario);
$I->am('an inactive member');
$I->wantTo('post to the legacy endpoint and get an NOTFOUND back');

$user = $I->getInactiveKeyholderMember();
$keyFob = $I->getMemberKeyFob($user->id);

//Post the keyfob to the endpoint
$I->sendPOST('/access-control/legacy', ['data'=>$keyFob->key_id.':00:00']);

//The legacy endpoint always returns 200
$I->seeResponseCodeIs(200);

//Make sure a failure is returned
$I->canSeeResponseEquals('NOTFOUND');

//Confirm an access log record was created
$I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>402, 'service'=>'main-door']);
