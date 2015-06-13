<?php
use \ApiTester;

class AccessControlCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function successfullMemberLogin(ApiTester $I)
    {
        $I->am('an active member');
        $I->wantTo('post to the main door endpoint and get an OK back');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>$keyFob->key_id]);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Make sure a good response is returned
        $I->seeResponseIsJson();

        //Response contains their name
        $I->seeResponseContains($user->given_name);

        //Make sure the request was allowed
        $I->seeResponseContainsJson(['valid'=>'1']);
        $I->dontSeeHttpHeader('Set-Cookie');
        $I->dontSeeHttpHeader('Built-By');

        //Confirm an access log record was created
        $I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>200, 'service'=>'main-door']);
    }

    public function unknownMemberLogin(ApiTester $I)
    {
        $I->am('an unknown member');
        $I->wantTo('post to the main door endpoint and get an unknown back');

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>'ABC12345678']);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Make sure a good response is returned
        $I->seeResponseIsJson();

        //Make sure the request was allowed
        $I->seeResponseContainsJson(['valid'=>'0']);
    }

    public function inactiveMemberLogin(ApiTester $I)
    {
        $I->am('an inactive member');
        $I->wantTo('post to the main door endpoint and get an OK back');

        $user = $I->getInactiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>$keyFob->key_id]);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Make sure a good response is returned
        $I->seeResponseIsJson();

        //Response contains their name
        //$I->seeResponseContains($user->given_name);

        //Make sure the request was allowed
        $I->seeResponseContainsJson(['valid'=>'0']);

        //Confirm an access log record was created
        $I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>402, 'service'=>'main-door']);
    }

    public function validSystemMessage(ApiTester $I)
    {
        $I->am('sending a valid system message');
        $I->wantTo('confirm it is received and handled correctly');

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>':main-door|boot']);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Confirm an access log record was created
        //$I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>200, 'service'=>'main-door']);
    }

    public function invalidSystemMessage(ApiTester $I)
    {
        $I->am('sending a valid system message');
        $I->wantTo('confirm it is received and handled correctly');

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>':main-door|unknown']);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Confirm an access log record was created
        //$I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>200, 'service'=>'main-door']);
    }

    public function unknownDeviceSystemMessage(ApiTester $I)
    {
        $I->am('sending a valid system message');
        $I->wantTo('confirm it is received and handled correctly');

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/main-door', ['data'=>':unknown|unknown']);

        //The endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Confirm an access log record was created
        //$I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>200, 'service'=>'main-door']);
    }

}