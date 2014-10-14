<?php
use Carbon\Carbon;

$I = new UnitTester($scenario);
$I->wantTo('confirm the grace period dates are correct');

$paypalDate = \BB\Helpers\MembershipPayments::getSubGracePeriodDate('paypal');

//Make sure its a date that's returned
$I->assertEquals(get_parent_class($paypalDate), 'DateTime');

//Confirm the date is what we expect
$I->assertEquals(Carbon::now()->subDays(7), $paypalDate);

