<?php namespace BB\Services;

use BB\Entities\SubscriptionCharge;
use BB\Entities\User;
use BB\Events\MemberBalanceChanged;
use BB\Events\SubscriptionPayment;
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

        //Check each of the due charges, if they have previous attempted payments ignore them
        // we don't want to retry failed payments as for DD's this will generate bank charges
        $subCharges->reject(function ($charge) {
            return $this->paymentRepository->getPaymentsByReference($charge->id)->count() > 0;
        });

        //Filter the list into two gocardless and balance subscriptions
        $goCardlessUsers = $subCharges->filter(function ($charge) {
            return $charge->user->payment_method == 'gocardless-variable';
        });

        $balanceUsers = $subCharges->filter(function ($charge) {
            return $charge->user->payment_method == 'balance';
        });


        //Charge the balance users
        foreach ($balanceUsers as $charge) {
            if (($charge->user->monthly_subscription * 100) > $charge->user->cash_balance) {
                //user doesn't have enough money

                //If they have a secondary payment method of gocardless try that
                if ($charge->user->secondary_payment_method == 'gocardless-variable') {

                    //Add the charge to the gocardless list for processing
                    $goCardlessUsers->push($charge);

                    event(new SubscriptionPayment\InsufficientFundsTryingDirectDebit($charge->user->id, $charge->id));
                } else {
                    event(new SubscriptionPayment\FailedInsufficientFunds($charge->user->id, $charge->id));
                }
                continue;
            }

            $this->paymentRepository->recordSubscriptionPayment($charge->user->id, 'balance', '', $charge->user->monthly_subscription, 'paid', 0, $charge->id);

            event(new MemberBalanceChanged($charge->user->id));
        }


        //Charge the gocardless users
        foreach ($goCardlessUsers as $charge) {
            /** @var SubscriptionCharge $charge */
            $amount = $charge->user->monthly_subscription;
            $bill = $this->goCardless->newBill($charge->user->mandate_id, ($amount * 100), $this->goCardless->getNameFromReason('subscription'));
            if ($bill) {
                $status = $bill->status;
                if ($status == 'pending_submission') {
                    $status = 'pending';
                }
                $this->paymentRepository->recordSubscriptionPayment($charge->user->id, 'gocardless-variable', $bill->id, $amount, $status, 0, $charge->id);
            }
        };

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
