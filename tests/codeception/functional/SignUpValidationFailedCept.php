<?php

$I = new FunctionalTester($scenario);

$I->am('a guest');
$I->wantTo('sign up to build brighton with missing information');


$I->amOnPage('/');
$I->click('Become a Member');


$I->seeCurrentUrlEquals('/register');

$I->fillField('First Name', 'Jon');
$I->fillField('Family Name', 'Doe');
$I->fillField('Email', 'jondoe@example.com');
$I->fillField('Password', '12345678');
$I->fillField(['name'=>'address[line_1]'], 'Street Address');
$I->fillField(['name'=>'address[postcode]'], 'AB12 3CD');
//$I->fillField('Emergency Contact', 'Contact Details');
//$I->attachFile('Profile Photo', 'test-image.png');

//\PHPUnit_Framework_TestCase::setExpectedException('\BB\Exceptions\FormValidationException');
$I->click('Join');

$I->seeCurrentUrlEquals('/register');