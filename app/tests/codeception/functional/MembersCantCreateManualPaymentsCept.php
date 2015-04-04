<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('member');
$I->wantTo('confirm I cant create manual payments');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

\PHPUnit_Framework_TestCase::setExpectedException('BB\Exceptions\AuthenticationException');
$I->sendPost('account/'.$user->id.'/payment', ['reason'=>'door-key', 'source'=>'manual', 'amount'=>5]);
