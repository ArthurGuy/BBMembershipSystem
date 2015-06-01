<?php
use Carbon\Carbon;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view recent member activity');

$user = $I->getActiveKeyholderMember();
$I->amLoggedAs($user);
$keyFob = $I->getMemberKeyFob($user->id);

$I->haveInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>'200', 'service'=>'main-door', 'created_at'=>Carbon::now()]);


$I->amOnPage('activity');
//$I->seeCurrentUrlEquals('activity?date=2014-10-01');
$I->see($user->name);