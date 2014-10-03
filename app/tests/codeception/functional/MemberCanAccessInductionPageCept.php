<?php 
$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view the equipment training page');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Equipment Training');
$I->click('Equipment Training');

$I->canSeeCurrentUrlEquals('/equipment_training');

$I->seeResponseCodeIs(200);