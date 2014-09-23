<?php
$I = new FunctionalTester($scenario);
$I->am('a guest');
$I->wantTo('try and fail to view the stats page');

//I can see the menu item
$I->amOnPage('/');
$I->canSee('Stats');

$I->haveEnabledFilters();

//I cant access it
$I->amOnPage('/stats');
$I->canSeeCurrentUrlEquals('/login');
