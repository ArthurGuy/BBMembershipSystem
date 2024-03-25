<?php
use BB\Entities\Payment;
use BB\Entities\User;

$I = new UnitTester($scenario);
$I->wantTo('confirm the payment helper fetches the correct payment date');

//Create a user record
$user = User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);

$date = \BB\Helpers\MembershipPayments::lastUserPaymentDate($user->id);
$I->assertFalse($date, 'Date should be false as no payments exist');


//Create some payment records

\BB\Entities\SubscriptionCharge::create([
    'user_id'        => $user->id,
    'charge_date'    => '2014-01-01',
    'payment_date'   => '2014-01-01',
    'status'         => 'paid',
]);
Payment::create(
    [
        'reason'           => 'subscription',
        'source'           => 'other',
        'user_id'          => $user->id,
        'amount'           => 25,
        'amount_minus_fee' => 25,
        'status'           => 'paid',
        'created_at'       => '2014-01-01'
    ]
);

\BB\Entities\SubscriptionCharge::create([
    'user_id'       => $user->id,
    'charge_date'   => '2014-06-01',
    'status'        => 'processing',
]);
Payment::create(
    [
        'reason'           => 'subscription',
        'source'           => 'other',
        'user_id'          => $user->id,
        'amount'           => 25,
        'amount_minus_fee' => 25,
        'status'           => 'pending',
        'created_at'       => '2014-06-01'
    ]
);

\BB\Entities\SubscriptionCharge::create([
    'user_id'       => $user->id,
    'charge_date'   => '2014-08-01',
    'status'        => 'cancelled',
]);
Payment::create(
    [
        'reason'           => 'subscription',
        'source'           => 'other',
        'user_id'          => $user->id,
        'amount'           => 25,
        'amount_minus_fee' => 25,
        'status'           => 'cancelled',
        'created_at'       => '2014-08-01'
    ]
);


//Now we have some payments re-fetch the last payment date
$date = \BB\Helpers\MembershipPayments::lastUserPaymentDate($user->id);

//Make sure its a date that's returned
$I->assertEquals(get_parent_class($date), 'DateTime');

//Confirm the datetime matched the first payment record, the only paid one
$I->assertEquals(new \Carbon\Carbon('2014-01-01'), $date);
