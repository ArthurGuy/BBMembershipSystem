<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('confirm I cant see other peoples edit page');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

$otherUser = User::find(3);

$I->amOnPage('/account/'.$otherUser->id.'/profile/edit');
$I->seeResponseCodeIs(403);