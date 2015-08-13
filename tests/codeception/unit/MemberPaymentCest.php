<?php

class MemberPaymentCest
{
    public function _before(UnitTester $I)
    {
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function paymentDateChange(UnitTester $I)
    {
        $user = \BB\Entities\User::create(['given_name'=>'Jon', 'family_name'=>'Doe', 'email'=>'month-test@example.com']);
        $I->assertEquals(0, $user->payment_day);

        $user->payment_day = 10;
        $user->save();
        $I->assertEquals(10, $user->payment_day);

        $user->payment_day = 28;
        $user->save();
        $I->assertEquals(28, $user->payment_day);

        $user->payment_day = 31;
        $user->save();
        $I->assertEquals(1, $user->payment_day);
    }
}