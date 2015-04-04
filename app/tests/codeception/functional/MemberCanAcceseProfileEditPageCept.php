<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view the profile edit page');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

$I->amOnPage('/account/'.$user->id);
$I->seeResponseCodeIs(200);
$I->canSeeCurrentUrlEquals('/account/'.$user->id);

//$I->click('Edit Your Profile');
$I->canSee('Edit Your Profile');

$I->amOnPage('/account/'.$user->id.'/profile/edit');
$I->seeResponseCodeIs(200);
$I->canSeeCurrentUrlEquals('/account/'.$user->id.'/profile/edit');
