<?php
use Carbon\Carbon;
use \FunctionalTester;
Use Mockery as m;

class SubscriptionChargeRepoCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function findExistingCharge(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId     = 22;
        $chargeDate = Carbon::now();

        $repo->createCharge($userId, $chargeDate);

        $I->assertTrue($repo->chargeExists($userId, $chargeDate), 'Existing charge not found');
    }

    public function returnExistingCharges(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId      = 23;
        $amount      = rand(5, 30);
        $chargeDate1 = Carbon::now()->subMonth();
        $chargeDate2 = Carbon::now();

        $repo->createCharge($userId, $chargeDate1, $amount);
        $repo->createCharge($userId, $chargeDate2, $amount);

        $charges = $repo->getMemberCharges($userId);
        $I->assertEquals(2, $charges->count());

        $I->assertEquals($amount, $charges->first()->amount);
        $I->assertEquals($chargeDate2->setTime(0, 0, 0), $charges->first()->charge_date);
    }

    /**
     * Return all pending charges
     *
     * @param FunctionalTester $I
     */
    public function returnPendingCharges(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId      = 24;
        $amount      = rand(5, 30);
        $chargeDate1 = Carbon::now()->subMonth();
        $chargeDate2 = Carbon::now();

        $repo->createCharge($userId, $chargeDate1, $amount, 'due');
        $repo->createCharge($userId, $chargeDate2, $amount);

        $charges = $repo->getPending();
        $I->assertGreaterThan(0, $charges->count());
    }

    /**
     * Return all due charges
     * @param FunctionalTester $I
     */
    public function returnDueCharges(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId      = 25;
        $amount      = rand(5, 30);
        $chargeDate1 = Carbon::now()->subMonth();
        $chargeDate2 = Carbon::now();

        $repo->createCharge($userId, $chargeDate1, $amount, 'due');
        $repo->createCharge($userId, $chargeDate2, $amount);

        $charges = $repo->getDue();
        $I->assertGreaterThan(0, $charges->count());
    }

    /**
     * Set a charges status to due
     * @param FunctionalTester $I
     */
    public function makeChargeDue(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId     = 26;
        $amount     = rand(5, 30);
        $chargeDate = Carbon::now();

        $newCharge = $repo->createCharge($userId, $chargeDate, $amount);
        $newChargeId = $newCharge->id;

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'status' => 'pending']);

        $repo->setDue($newChargeId);

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'status' => 'due']);
    }

    /**
     * Updates a charges amount
     * @param FunctionalTester $I
     */
    public function updateChargeAmount(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId     = 27;
        $amount     = rand(5, 30);
        $chargeDate = Carbon::now();

        $newCharge = $repo->createCharge($userId, $chargeDate, $amount);
        $newChargeId = $newCharge->id;

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'amount' => $amount]);

        $repo->updateAmount($newChargeId, 30);

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'amount' => 30]);
    }

    /**
     * Set a charges status to failed
     * @param FunctionalTester $I
     */
    public function markChargeAsFailed(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId     = 28;
        $amount     = rand(5, 30);
        $chargeDate = Carbon::now();

        $newCharge = $repo->createCharge($userId, $chargeDate, $amount, 'processing');
        $newChargeId = $newCharge->id;

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'status' => 'processing', 'amount' => $amount]);

        $repo->paymentFailed($newChargeId);

        $I->seeInDatabase('subscription_charge', ['id' => $newChargeId, 'status' => 'due', 'amount' => 0]);
    }

    public function checkForOutstandingCharges(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId      = 29;
        $amount      = rand(5, 30);
        $chargeDate1 = Carbon::now()->subMonth();
        $chargeDate2 = Carbon::now();

        $repo->createCharge($userId, $chargeDate1, $amount, 'due');
        $repo->createCharge($userId, $chargeDate2, $amount);

        $I->assertTrue($repo->hasOutstandingCharges($userId), 'Outstanding charges found');
    }

    public function cancelOutstandingCharges(FunctionalTester $I)
    {
        $repo = app('\BB\Repo\SubscriptionChargeRepository');
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */

        $userId      = 30;
        $amount      = rand(5, 30);
        $chargeDate1 = Carbon::now()->subMonth();
        $chargeDate2 = Carbon::now();

        $repo->createCharge($userId, $chargeDate1, $amount, 'due');
        $repo->createCharge($userId, $chargeDate2, $amount);

        $I->assertTrue($repo->hasOutstandingCharges($userId), 'No outstanding charges found');

        $repo->cancelOutstandingCharges($userId);

        $I->assertFalse($repo->hasOutstandingCharges($userId), 'Outstanding charges found');
    }

    /**
     * Confirm the gocardless method gets correctly called
     *
     * @param FunctionalTester $I
     */
    public function chargeAndBillUser(FunctionalTester $I)
    {
        $authId     = str_random();
        $amount     = rand(5, 30);
        $userId     = 31;
        $chargeDate = Carbon::now()->day(10);

        //Generate helper mock
        $goCardlessHelper = m::mock('\BB\Helpers\GoCardlessHelper');
        $goCardlessHelper->shouldReceive('getNameFromReason')->withArgs(['subscription'])->once()->andReturn('Subscription');
        $goCardlessHelper->shouldReceive('newBill')->withArgs([$authId, $amount, 'Subscription'])->once()->andReturn(false);

        $repo = new \BB\Repo\SubscriptionChargeRepository(app('\BB\Entities\SubscriptionCharge'), app('\BB\Repo\PaymentRepository'), $goCardlessHelper);

        //Call the method and confirm the mock gets called correctly
        $repo->createChargeAndBillDD($userId, $chargeDate, $amount, 'processing', $authId);

        $I->seeInDatabase('subscription_charge', ['user_id' => $userId, 'amount' => $amount]);
    }
}