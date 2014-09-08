<?php 
$I = new FunctionalTester($scenario);

$I->am('member');
$I->wantTo('confirm I can see member menu items only');


$user = User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);
Auth::login($user);

$I->amOnPage('/');

$I->canSeeLink('Your Membership');
$I->canSeeLink('Logout');

$I->cantSee('Admin');