<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a guest');
$I->wantTo('make sure I can\'t see private members in the member list');

//Load a known member
$user = User::find(1);
$user->profile_private = 1;
$user->save();

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Members');
$I->click('Members');

$I->canSeeCurrentUrlEquals('/members');
$I->canSee('Members');

//Make sure the user isnt listed
$I->cantSee($user->name);

$I->amOnPage('/members/'.$user->id);
$I->canSeeResponseCodeIs(404);