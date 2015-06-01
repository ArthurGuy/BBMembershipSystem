<?php 
$I = new FunctionalTester($scenario);
$I->wantTo('test the notifications display on the page');
/*
\Notification::message('General Message');
$I->amOnPage('/');
$I->see('General Message');
$I->seeElement('#snackbarMessage');


\Notification::success('Success Message');
$I->amOnPage('/');
$I->see('Success Message');
$I->seeElement('#snackbarMessage');


\Notification::error('Error Message');
$I->amOnPage('/');
$I->see('Error Message');
$I->seeElement('#snackbarMessage');


$details = new \Illuminate\Support\MessageBag(['line1'=>'Error Line 1', 'line2'=>'Error Line 2']);
\Notification::error('Error Message', $details);
$I->amOnPage('/');
$I->see('Error Message');
$I->see('Error Line 1');
$I->see('Error Line 2');
$I->seeElement('#snackbarMessage');
$I->seeElement('#snackbarMessages');

*/