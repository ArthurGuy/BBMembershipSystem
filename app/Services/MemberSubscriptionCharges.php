<?php namespace BB\Services;

use BB\Entities\User;
use BB\Helpers\GoCardlessHelper;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use BB\Repo\UserRepository;
use Carbon\Carbon;

class MemberSubscriptionCharges
{

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
            if (($user->payment_day == $targetDate->day) && ( ! $this->subscriptionChargeRepository->chargeExists($user->id, $targetDate))) {
                $this->subscriptionChargeRepository->createCharge($user->id, $targetDate);
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
            if ($charge->charge_date->isToday() || $charge->charge_date->isPast()) {
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
                    continue;
                }
                
                $bill = $this->goCardless->newBill($charge->user->subscription_id, $charge->user->monthly_subscription, $this->goCardless->getNameFromReason('subscription'));
                if ($bill) {
                    $this->paymentRepository->recordSubscriptionPayment($charge->user->id, 'gocardless-variable', $bill->id, $bill->amount, $bill->status, $bill->gocardless_fees, $charge->id);
                }
            }
        }
    }

    /**
     * Get a users latest sub payment
     * @param $userId
     * @return bool
     */
    public function lastUserChargeExpires($userId)
    {
        $charge = User::where('user_id', $userId)->where('status', ['processing', 'paid'])->orderBy('charge_date', 'DESC')->first();
        if ($charge) {
            return $charge->charge_date->addMonth();
        }
        return false;
    }
}