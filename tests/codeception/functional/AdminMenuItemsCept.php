<?php 
$I = new FunctionalTester($scenario);

$I->am('an admin user');
$I->wantTo('see the admin menu items');

$I->loginAdminMember();

$I->amOnPage('/');

$I->canSee('Admin');