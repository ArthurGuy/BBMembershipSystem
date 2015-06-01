<?php 
$I = new FunctionalTester($scenario);

$I->am('guest');

$I->wantTo('make sure I cant see member menu items');

$I->amOnPage('/');
$I->cantSeeLink('Your Membership');
$I->canSeeLink('Become a Member');
$I->canSeeLink('Login');