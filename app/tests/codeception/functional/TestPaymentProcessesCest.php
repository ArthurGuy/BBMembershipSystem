<?php
use Carbon\Carbon;
use \FunctionalTester;

class TestPaymentProcessesCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Confirm sub charge records can be created and fetched
     *
     * @param FunctionalTester $I
     */
    public function testSubChargeFetching(FunctionalTester $I)
    {
        $subChargeRepo = App::make('\BB\Repo\SubscriptionChargeRepository');

        $subChargeRepo->createCharge(10, Carbon::now());

        $charge = $subChargeRepo->findCharge(10);
        $I->assertNotNull($charge);
        $I->assertEquals(10, $charge->user_id);
    }

    /**
     * Make sure payment records can be created
     *
     * @param FunctionalTester $I
     */
    public function createTestPayment(FunctionalTester $I)
    {
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());
        $paymentRepo->recordPayment('test', 11, 'test', 1, 10, 'pending');

        $I->seeInDatabase('payments', ['reason'=>'test', 'user_id'=>11, 'source'=>'test']);
    }

    /**
     * Make sure subscription payments can be created
     *
     * @param FunctionalTester $I
     */
    public function createSubPayment(FunctionalTester $I)
    {
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());
        $paymentRepo->recordSubscriptionPayment(12, 'test', 1, 10, 'pending');

        $I->seeInDatabase('payments', ['reason'=>'subscription', 'user_id'=>12, 'source'=>'test', 'reference'=>'']);
    }

    /**
     * Record a payment with an associated sub charge and confirm the payment gets tagged with the sub charge id
     *
     * @param FunctionalTester $I
     */
    public function createSubPaymentWithSubCharge(FunctionalTester $I)
    {
        $I->haveInDatabase('users', ['id'=>13, 'active'=>1, 'status'=>'active', 'monthly_subscription'=>10]);

        $subChargeRepo = App::make('\BB\Repo\SubscriptionChargeRepository');
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());

        $subCharge = $subChargeRepo->createCharge(13, Carbon::now(), 0, 'due');

        //The sub charge should be in the database
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'user_id'=>13, 'status'=>'due']);

        try {
            $paymentRepo->recordSubscriptionPayment(13, 'test', 1, 10, 'pending');
        } catch (Exception $e) {
            die($e->getFile().':'.$e->getLine().':'.$e->getMessage());
        }

        $I->seeInDatabase('payments', ['reason'=>'subscription', 'user_id'=>13, 'source'=>'test', 'reference'=>$subCharge->id]);

        //The subscription charge status should now be processing
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'user_id'=>13, 'status'=>'processing']);
    }

    /**
     * User with two sub charges - make sure the correct one gets used
     *
     * @param FunctionalTester $I
     */
    public function correctSubChargeGetsTargeted(FunctionalTester $I)
    {
        $I->haveInDatabase('users', ['id'=>14, 'active'=>1, 'status'=>'active', 'monthly_subscription'=>10]);

        $subChargeRepo = App::make('\BB\Repo\SubscriptionChargeRepository');
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());

        $subCharge1 = $subChargeRepo->createCharge(14, Carbon::now()->subMonth(), 0, 'due');
        $subCharge2 = $subChargeRepo->createCharge(14, Carbon::now()->subDay(), 0, 'due');

        $paymentRepo->recordSubscriptionPayment(14, 'test', 1, 10, 'pending');

        //The oldest sub payment should have been used
        $I->seeInDatabase('payments', ['reason'=>'subscription', 'user_id'=>14, 'source'=>'test', 'reference'=>$subCharge1->id]);

        //The subscription charge status should now be processing
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge1->id, 'user_id'=>14, 'status'=>'processing']);

        //The newer sub charge shouldnt have been touched
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge2->id, 'user_id'=>14, 'status'=>'due']);
    }

    /**
     * Record a payment with an associated sub charge and confirm the user expiry date gets updated
     *
     * @param FunctionalTester $I
     */
    public function subPaymentsUpdateUserExpiry(FunctionalTester $I)
    {
        $I->haveInDatabase('users', ['id'=>15, 'active'=>0, 'status'=>'suspended', 'monthly_subscription'=>10, 'subscription_expires'=>'2015-01-01']);

        $subChargeRepo = App::make('\BB\Repo\SubscriptionChargeRepository');
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());

        //Create a charge with a due date the same as the user expiry date
        $subChargeDate = Carbon::create(2015, 1, 1, 0, 0, 0);
        $subCharge = $subChargeRepo->createCharge(15, $subChargeDate, 0, 'due');

        //The sub charge should be in the database
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'user_id'=>15, 'status'=>'due']);

        //The payment comes in half way through the month
        $paymentDate = Carbon::create(2015, 1, 18, 0, 0, 0);
        $paymentRepo->recordSubscriptionPayment(15, 'test', 1, 10, 'pending', 0, '', $paymentDate);

        $I->seeInDatabase('payments', ['reason'=>'subscription', 'user_id'=>15, 'source'=>'test', 'reference'=>$subCharge->id]);

        //The subscription charge status should now be processing
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'user_id'=>15, 'status'=>'processing']);

        //The users expiry date should be one month on from the sub charge date
        $newExpiryDate = $subChargeDate->addMonth()->format('Y-m-d');
        $I->seeInDatabase('users', ['id'=>15, 'active'=>1, 'status'=>'active', 'subscription_expires'=>$newExpiryDate]);
    }

    /**
     * Payment status changes from pending to paid
     *
     * @param FunctionalTester $I
     */
    public function paymentStatusChange(FunctionalTester $I)
    {
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());
        $paymentId = $paymentRepo->recordSubscriptionPayment(16, 'test', 1, 10, 'pending');

        $I->seeInDatabase('payments', ['id'=>$paymentId, 'reason'=>'subscription', 'user_id'=>16, 'source'=>'test', 'reference'=>'', 'status'=>'pending']);

        $paymentDate = Carbon::create(2015, 1, 18, 0, 0, 0);
        $paymentRepo->markPaymentPaid($paymentId, $paymentDate);

        $I->seeInDatabase('payments', ['id'=>$paymentId, 'reason'=>'subscription', 'user_id'=>16, 'source'=>'test', 'reference'=>'', 'status'=>'paid']);
    }

    /**
     * Payment status change updates sub charge
     *
     * @param FunctionalTester $I
     */
    public function paymentStatusSubChargeChange(FunctionalTester $I)
    {
        $I->haveInDatabase('users', ['id'=>17, 'active'=>1, 'status'=>'active', 'monthly_subscription'=>15]);

        $subChargeRepo = App::make('\BB\Repo\SubscriptionChargeRepository');
        $paymentRepo = new \BB\Repo\PaymentRepository(new \BB\Entities\Payment());

        $subChargeDate = Carbon::create(2015, 1, 10, 0, 0, 0);
        $subCharge = $subChargeRepo->createCharge(17, $subChargeDate, 0, 'due');

        //Sub charge is due
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'status'=>'due', 'amount'=>0]);

        $paymentId = $paymentRepo->recordSubscriptionPayment(17, 'test', 1, 10, 'pending');

        $I->seeInDatabase('payments', ['id'=>$paymentId, 'reason'=>'subscription', 'source'=>'test', 'reference'=>$subCharge->id, 'status'=>'pending']);

        //When a payment has started the sub charge should be processing
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'status'=>'processing', 'amount'=>10]);

        $paymentDate = Carbon::create(2015, 1, 18, 0, 0, 0);
        $paymentRepo->markPaymentPaid($paymentId, $paymentDate);

        $I->seeInDatabase('payments', ['id'=>$paymentId, 'reason'=>'subscription', 'source'=>'test', 'reference'=>$subCharge->id, 'status'=>'paid']);

        //When the payment is paid the charge should be paid with a value and date
        $I->seeInDatabase('subscription_charge', ['id'=>$subCharge->id, 'status'=>'paid', 'amount'=>10,  'payment_date'=>$paymentDate->format('Y-m-d')]);
    }

}