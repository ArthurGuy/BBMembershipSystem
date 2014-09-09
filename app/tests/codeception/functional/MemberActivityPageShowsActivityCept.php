<?php 
$I = new FunctionalTester($scenario);
$I->am('a member');
$I->wantTo('look at activity on the activity page');

$user = $I->createMember();
Auth::login($user);

$I->amOnPage('activity');

$I->see('Activity Log');
$I->seeLink('Previous');
