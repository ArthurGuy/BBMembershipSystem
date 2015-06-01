<?php
use BB\Entities\User;

$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('view the stats page');

//Load and login a known member
$user = User::find(1);
Auth::login($user);

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Stats');

//Try and go to the stats page
$I->amOnPage('/stats');

$I->canSeeCurrentUrlEquals('/stats');
$I->canSee('Payment Methods');