<?php
use \ApiTester;

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
        $I->sendPOST('/acs', ['device'=>'main-door', 'message'=>'boot', 'type'=>'door']);

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
        $I->sendPOST('/acs', ['device'=>'main-door', 'key_fob'=>$keyFob, 'message'=>'lookup', 'type'=>'door']);

        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseContainsJson(['valid'=>'1']);
    }

    public function missingDevice(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing device');

        //Send a bad code to the endpoint
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\FormValidationException', function() use ($I){
                $I->haveHttpHeader('Content-Type', 'application/json');
                $I->sendPOST('/acs', ['device'=>'', 'key_fob'=>'ABCDEF123456', 'message'=>'boot', 'type'=>'door']);
            })
        );
    }

    public function missingMessage(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing message');

        //Send a bad code to the endpoint
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\FormValidationException', function() use ($I){
                $I->haveHttpHeader('Content-Type', 'application/json');
                $I->sendPOST('/acs', ['device'=>'main-door', 'key_fob'=>'ABCDEF123456', 'message'=>'', 'type'=>'door']);
            })
        );
    }

    public function missingType(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - missing type');

        //Send a bad code to the endpoint
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\FormValidationException', function() use ($I){
                $I->haveHttpHeader('Content-Type', 'application/json');
                $I->sendPOST('/acs', ['device'=>'main-door', 'key_fob'=>'ABCDEF123456', 'message'=>'boot', 'type'=>'']);
            })
        );
    }

    public function invalidTime(ApiTester $I)
    {
        $I->am('an invalid device');
        $I->wantTo('verify the endpoint returns validation failures - invalid time');

        //Send a bad code to the endpoint
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\FormValidationException', function() use ($I){
                $I->haveHttpHeader('Content-Type', 'application/json');
                $I->sendPOST('/acs', ['device'=>'main-door', 'key_fob'=>'ABCDEF123456', 'message'=>'boot', 'type'=>'door', 'time'=>'10000000000']);
            })
        );
    }

}