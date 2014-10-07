<?php 
$I = new FunctionalTester($scenario);
$I->am('a guest');
$I->wantTo('view a members profile page');

$I->amOnPage('members');

$I->click('Jon Doe');
$I->seeCurrentUrlEquals('/members/1');
$I->see('Jon Doe');