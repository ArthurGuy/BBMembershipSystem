<?php namespace BB\Services;

use BB\Helpers\GoCardlessHelper;
use BB\Repo\SubscriptionChargeRepository;
use BB\Repo\UserRepository;

class MemberSubscriptionCharges {

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;
    /**
     * @var GoCardlessHelper
     */
    private $goCardless;

    function __construct(UserRepository $userRepository, SubscriptionChargeRepository $subscriptionChargeRepository, GoCardlessHelper $goCardless)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
        $this->goCardless = $goCardless;
    }

    public function createSubscriptionCharges($targetDate)
    {
        $users = $this->userRepository->getActive();
        foreach ($users as $user) {
            if (($user->payment_day == $targetDate->day) && (!$this->subscriptionChargeRepository->chargeExists($user->id, $targetDate))) {
                $this->subscriptionChargeRepository->createCharge($user->id, $targetDate, $user->monthly_subscription);
            }
        }
    }

    public function billMembers()
    {
        $subCharges = $this->subscriptionChargeRepository->getDraft();

        foreach ($subCharges as $charge) {
            if ($charge->user->payment_method == 'gocardless-variable') {
                $bill = $this->goCardless->newBill($charge->user->subscription_id, $charge->user->monthly_subscription);
                if ($bill) {
                    $this->subscriptionChargeRepository->markChargeAsPaid($charge->id);
                }
            }
        }


        /*
        if ($bill)
        {
            $payment = new Payment([
                'reason'            => 'subscription',
                'source'            => 'gocardless-variable',
                'source_id'         => $bill->id,
                'amount'            => $bill->amount,
                'fee'               => $bill->gocardless_fees,
                'amount_minus_fee'  => $bill->amount_minus_fees,
                'status'            => $bill->status,
                'reference'         => $subCharge->id,
            ]);
            $user->payments()->save($payment);
            $user->last_subscription_payment = Carbon::now();
            $user->save();

            $this->subscriptionChargeRepository->markChargeAsPaid($subCharge->id, Carbon::now());

            $user->extendMembership('gocardless-variable', \Carbon\Carbon::now()->addMonth());

            Notification::success("Your subscription has been setup, thank you");
        }
        else
        {
            //something went wrong - we still have the pre auth though
            Notification::success("There was a problem setting up your subscription");
        }
        */
    }

}