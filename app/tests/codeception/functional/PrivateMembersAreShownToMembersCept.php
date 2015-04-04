<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('make sure I can see private members in the member list');

//Load a known member
$user = User::find(1);
$user->profile_private = 1;
$user->save();


$user2 = User::find(3);
Auth::login($user2);

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Members');
$I->click('Members');

$I->canSeeCurrentUrlEquals('/members');
$I->canSee('Members');

//Make sure the user is listed
$I->click($user->name);
$I->canSeeCurrentUrlEquals('/members/'.$user->id);
$I->canSeeResponseCodeIs(200);