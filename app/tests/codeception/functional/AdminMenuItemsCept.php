<?php 
$I = new FunctionalTester($scenario);

$I->am('an admin user');
$I->wantTo('see the admin menu items');

$I->loginAdminMember();

$I->haveEnabledFilters();
$I->amOnPage('/');

$I->canSee('Admin');