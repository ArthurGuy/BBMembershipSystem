<?php 
$I = new UnitTester($scenario);
$I->wantTo('confirm notifications can be set and retrieved ');

\Notification::message('General Message');

$I->assertEquals('General Message', Notification::getMessage());
