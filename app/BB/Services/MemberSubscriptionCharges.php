<?php namespace BB\Services;

use BB\Helpers\GoCardlessHelper;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use BB\Repo\UserRepository;
use Carbon\Carbon;

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
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    function __construct(UserRepository $userRepository, SubscriptionChargeRepository $subscriptionChargeRepository, GoCardlessHelper $goCardless, PaymentRepository $paymentRepository)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
        $this->goCardless = $goCardless;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Create the sub charge for each member, only do this for members with dates matching the supplied date
     *
     * @param Carbon $targetDate
     */
    public function createSubscriptionCharges($targetDate)
    {
        $users = $this->userRepository->getBillableActive();
        foreach ($users as $user) {
            if (($user->payment_day == $targetDate->day) && (!$this->subscriptionChargeRepository->chargeExists($user->id, $targetDate))) {
                $this->subscriptionChargeRepository->createCharge($user->id, $targetDate, $user->monthly_subscription);
            }
        }
    }

    /**
     * Locate all charges that are for today or the past and mark them as due
     */
    public function makeChargesDue()
    {
        $subCharges = $this->subscriptionChargeRepository->getPending();
        foreach ($subCharges as $charge) {
            if ($charge->payment_date->isToday() || $charge->payment_date->isPast()) {
                $this->subscriptionChargeRepository->setDue($charge->id);
            }
        }
    }

    /**
     * Bill members based on the sub charges that are due
     */
    public function billMembers()
    {
        $subCharges = $this->subscriptionChargeRepository->getDue();

        foreach ($subCharges as $charge) {
            if ($charge->user->payment_method == 'gocardless-variable') {

                //Look the the previous attempts - there may be multiple failures
                $existingPayments = $this->paymentRepository->getPaymentsByReference($charge->id);
                if ($existingPayments->count() > 0) {
                    //We will let the user retry the payment if it fails
                    break;
                }
                
                $bill = $this->goCardless->newBill($charge->user->subscription_id, $charge->user->monthly_subscription);
                if ($bill) {
                    $this->paymentRepository->recordPayment('subscription', $charge->user->id, 'gocardless-variable', $bill->id, $bill->amount, $bill->status, $bill->gocardless_fees, $charge->id);
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

    /**
     * Get a users latest sub payment
     * @param $userId
     * @return bool
     */
    public function lastUserChargeExpires($userId)
    {
        $charge = $this->model->where('user_id', $userId)->where('status', ['processing', 'paid'])->orderBy('charge_date', 'DESC')->first();
        if ($charge) {
            return $charge->charge_date->addMonth();
        }
        return false;
    }
}