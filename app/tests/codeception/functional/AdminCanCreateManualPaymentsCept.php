<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('member');
$I->wantTo('confirm I cant create manual payments');

//Load and login a known member
$adminUser = User::find(3);
Auth::login($adminUser);

$user = User::find(1);

$I->sendPost('account/'.$user->id.'/payment', ['reason'=>'door-key', 'source'=>'manual', 'amount'=>5]);
