<?php 
$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view the equipment training page');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Tools and Equipment');
$I->click('Tools and Equipment');

$I->canSeeCurrentUrlEquals('/equipment');

$I->seeResponseCodeIs(200);