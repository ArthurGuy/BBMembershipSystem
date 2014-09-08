<?php

use \Mockery as m;

$I = new FunctionalTester($scenario);

$I->am('a guest');
$I->wantTo('sign up to build brighton');


$I->amOnPage('/');
$I->click('Become a Member');


$I->seeCurrentUrlEquals('/register');

$I->fillField('First Name', 'Jon');
$I->fillField('Family Name', 'Doe');
$I->fillField('Email', 'jondoe@example.com');
$I->fillField('Password', '12345678');
$I->fillField('Address Line 1', 'Street Address');
$I->fillField('Post Code', 'AB12 3CD');
$I->fillField('Emergency Contact', 'Contact Details');
$I->attachFile('Profile Photo', 'test-image.png');

//$userImageService = m::mock('\BB\Helpers\UserImage');
//$userImageService->shouldReceive('uploadPhoto')->times(1);
//$this->app->instance('\BB\Helpers\UserImage',$userImageService);

$I->click('Join');

$I->seeCurrentUrlEquals('/account/1');