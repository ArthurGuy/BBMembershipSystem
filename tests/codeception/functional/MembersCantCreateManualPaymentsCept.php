<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('member');
$I->wantTo('confirm I cant create manual payments');

//Load and login a known member
$user = User::find(1);
$I->amLoggedAs($user);

$I->sendPost('account/'.$user->id.'/payment', ['reason'=>'door-key', 'source'=>'manual', 'amount'=>5]);

$I->cantSeeResponseCodeIs(200);
$I->cantSeeResponseCodeIs(201);