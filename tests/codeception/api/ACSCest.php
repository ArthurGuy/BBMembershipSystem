<?php

class ACSCest
{

    public function validBoot(ApiTester $I)
    {
        $I->am('a valid device');
        $I->wantTo('verify the endpoint returns a success boot response');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'main-door', 'message'=>'boot', 'service'=>'entry']);

        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseContainsJson(['deviceStatus'=>'1']);
    }

    public function validDoorEntry(ApiTester $I)
    {
        $I->am('a valid device');
        $I->wantTo('verify the endpoint returns a success door lookup response');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'main-door', 'tag'=>$keyFob->key_id, 'message'=>'lookup', 'service'=>'entry', 'time'=>time()]);

        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseContainsJson(['valid'=>'1']);
    }

    public function missingDevice(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing device');

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'', 'tag'=>'ABCDEF123456', 'message'=>'boot', 'service'=>'entry']);
        $I->canSeeResponseCodeIs(422);

    }

    public function missingMessage(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing message');

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'main-door', 'tag'=>'ABCDEF123456', 'message'=>'', 'service'=>'entry']);
        $I->canSeeResponseCodeIs(422);
    }

    public function missingService(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing service');

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'main-door', 'tag'=>'ABCDEF123456', 'message'=>'boot', 'service'=>'']);
        $I->canSeeResponseCodeIs(422);
    }

    public function invalidTime(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - invalid time');

        //Send a bad code to the endpoint
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('Accept', 'application/json');
        $I->sendPOST('/acs', ['device'=>'main-door', 'tag'=>'ABCDEF123456', 'message'=>'boot', 'service'=>'entry', 'time'=>'abcdefgh']);
        $I->canSeeResponseCodeIs(422);
    }

}