<?php
use \ApiTester;

class LegacyAccessControlCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }
/*
    public function successfullMemberLogin(ApiTester $I)
    {
        $I->am('an active member');
        $I->wantTo('post to the legacy endpoint and get an OK back');

        $user = $I->getActiveKeyholderMember();
        $keyFob = $I->getMemberKeyFob($user->id);

        //Post the keyfob to the endpoint
        $I->sendPOST('/access-control/legacy', ['data'=>$keyFob->key_id.':00:00']);

        //The legacy endpoint always returns 200
        $I->seeResponseCodeIs(200);

        //Make sure a good response is returned
        $I->seeResponseEquals('OK:8F00:Jon Doe');

        //Confirm an access log record was created
        $I->seeInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>200, 'service'=>'main-door']);
    }

    public function inactiveMemberLogin(ApiTester $I)
    {
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
    }

    public function failedMemberLogin(ApiTester $I)
    {
        $I->am('not a member with a random tag');
        $I->wantTo('post to the legacy endpoint and confirm it fails');

        //Send a bad code to the endpoint
        $I->sendPOST('/access-control/legacy', ['data'=>'BADKEYFOB000:00:00']);

        //The legacy endpoint always returns 200
        $I->canSeeResponseCodeIs(200);

        //Make sure a failure is returned
        $I->canSeeResponseEquals('NOTFOUND');
    }
*/
}