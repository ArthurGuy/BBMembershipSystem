<?php
use BB\Services\DeviceCharge;

$I = new FunctionalTester($scenario);
$I->wantTo('confirm device charges get generated correctly');


$I->seeInDatabase('equipment_log', ['user_id'=>1, 'device'=>'laser', 'removed'=>0, 'billed'=>0]);
$I->seeInDatabase('equipment_log', ['user_id'=>1, 'device'=>'laser', 'removed'=>1, 'billed'=>0]);
$I->dontSeeInDatabase('payments', ['reason'=>'equipment-fee', 'reference'=>'1:laser']);



$deviceCharging = new DeviceCharge();
$deviceCharging->calculatePendingDeviceFees();


//The record that hasn't been removed should have been billed
$I->seeInDatabase('equipment_log', ['user_id'=>1, 'device'=>'laser', 'removed'=>0, 'billed'=>1]);
$I->seeInDatabase('equipment_log', ['user_id'=>1, 'device'=>'laser', 'removed'=>1, 'billed'=>0]);
$I->seeInDatabase('payments', ['reason'=>'equipment-fee', 'reference'=>'1:laser']);