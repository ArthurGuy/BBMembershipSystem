<?php

use BB\Entities\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

Class SubscriptionChargeTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_works()
    {
        /** @var \BB\Repo\SubscriptionChargeRepository $repo */
        $repo = $this->app->make(\BB\Repo\SubscriptionChargeRepository::class);

        $user = factory('BB\Entities\User')->create();
        $date = Carbon::now();
        $amount = 10;
        $charge = $repo->createCharge($user->id, $date, $amount);

        $this->expectsEvents('BB\Events\SubscriptionChargePaid');

        //Mark a charge as being paid, this should fire an event
        $repo->markChargeAsPaid($charge->id);
    }


}