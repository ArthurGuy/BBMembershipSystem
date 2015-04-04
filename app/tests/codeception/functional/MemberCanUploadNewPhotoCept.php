<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('update my profile photo');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

$I->amOnPage('/account/'.$user->id.'/profile/edit');
$I->canSee('Profile Photo');

$I->attachFile('Profile Photo', 'test-image.png');

$I->click('Save');

$I->seeCurrentUrlEquals('/members/'.$user->id);