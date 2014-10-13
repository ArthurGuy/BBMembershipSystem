<?php 
$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view recent member activity');

$user = $I->getActiveKeyholderMember();
$keyFob = $I->getMemberKeyFob($user->id);

$I->haveInDatabase('access_log', ['user_id'=>$user->id, 'key_fob_id'=>$keyFob->id, 'response'=>'200', 'service'=>'main-door', 'created_at'=>'2014-10-01 12:00:00']);

$I->amOnPage('activity?date=2014-10-01');
$I->see($user->name);