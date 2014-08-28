<?php

class MembershipPaymentsHelperTest extends TestCase
{

    public function testLastUserPaymentDateNoPayment()
    {
        $user = User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);

        $date = \BB\Helpers\MembershipPayments::lastUserPaymentDate($user->id);

        $this->assertFalse($date);
    }

    public function testLastUserPaymentDate()
    {
        $user = User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);
        Payment::create(
            [
                'reason'           => 'subscription',
                'source'           => 'other',
                'user_id'          => $user->id,
                'amount'           => 20,
                'amount_minus_fee' => 20,
                'status'           => 'paid',
                'created_at'       => '2014-06-01'
            ]
        );
        Payment::create(
            [
                'reason'           => 'subscription',
                'source'           => 'other',
                'user_id'          => $user->id,
                'amount'           => 20,
                'amount_minus_fee' => 20,
                'status'           => 'paid',
                'created_at'       => '2014-01-01'
            ]
        );

        $date = \BB\Helpers\MembershipPayments::lastUserPaymentDate($user->id);

        //Make sure its a date that's returned
        $this->assertInstanceOf('DateTime', $date);

        //Confirm the datetime is now, when the record above was created.
        $this->assertEquals(new \Carbon\Carbon('2014-06-01'), $date);
    }

} 