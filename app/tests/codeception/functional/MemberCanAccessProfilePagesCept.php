<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view a members profile');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Members');
$I->click('Members');

$I->canSeeCurrentUrlEquals('/members');
$I->canSee('Members');

$I->click($user->name);
$I->canSeeCurrentUrlEquals('/members/'.$user->id);
$I->canSee($user->name);
