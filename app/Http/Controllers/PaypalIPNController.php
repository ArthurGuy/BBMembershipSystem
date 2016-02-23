<?php namespace BB\Http\Controllers;

use BB\Entities\Payment;
use BB\Entities\User;
use BB\Helpers\PayPalConfig;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use Carbon\Carbon;

class PaypalIPNController extends Controller
{

    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;

    function __construct(SubscriptionChargeRepository $subscriptionChargeRepository, PaymentRepository $paymentRepository)
    {
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function receiveNotification()
    {
        $ipnMessage = new \PayPal\IPN\PPIPNMessage('', PayPalConfig::getConfig());

        if ( ! $ipnMessage->validate()) {
            \Log::error("Invalid IPN");
        }

        $ipnData = $ipnMessage->getRawData();

        if (isset($ipnData['txn_type']) && ($ipnData['txn_type'] == 'subscr_payment')) {
            if ($ipnData['payment_status'] != 'Completed') {
                \Log::error("PayPal IPN: Received unknown payment status for sub payment: \"" . $ipnData['payment_status'] . "\" Email: " . $ipnData['payer_email']);
                return;
            }
            $user = User::where('email', $ipnData['payer_email'])->first();
            if ( ! $user) {
                $user = User::where('secondary_email', $ipnData['payer_email'])->first();
            }
            if ( ! $user) {
                //\Log::error("PayPal IPN Received for unknown email " . $ipnData['payer_email']);
                $paymentId = $this->paymentRepository->recordPayment('donation', 0, 'paypal', $ipnData['txn_id'], $ipnData['mc_gross'], 'paid', $ipnData['mc_fee'], $ipnData['payer_email']);
                event(new \BB\Events\UnknownPayPalPaymentReceived($paymentId, $ipnData['payer_email']));
                return;
            }

            //It looks like the user might be joining again
            if ($user->status == 'left') {
                $user->rejoin();
            }

            //If its a new user set them up by creating the first sub charge record and setting the payment day
            if ($user->status == 'setting-up') {
                $this->setupNewMember($user, $ipnData['mc_gross']);
            }

            $date = Carbon::now();
            //If the user doesn't have an active sub charge record the payment as a balance payment
            $subCharge = $this->subscriptionChargeRepository->findCharge($user->id, $date);
            if ($subCharge) {
                //Record the subscription payment, this will automatically deal with locating the sub charge and updating that
                $this->paymentRepository->recordSubscriptionPayment($user->id, 'paypal', $ipnData['txn_id'],
                    $ipnData['mc_gross'], 'paid', $ipnData['mc_fee']);
            } else {
                $this->paymentRepository->recordPayment('balance', $user->id, 'paypal', $ipnData['txn_id'],
                    $ipnData['mc_gross'], 'paid', $ipnData['mc_fee']);
            }

        } elseif (isset($ipnData['txn_type']) && ($ipnData['txn_type'] == 'subscr_cancel')) {
            $user = User::where('email', $ipnData['payer_email'])->first();
            if ($user) {
                //The user may have already changed to another method, only cancel if its still paypal
                if ($user->payment_method == 'paypal') {
                    $user->cancelSubscription();
                }

                //@TODO: Deal with any open sub payment records
            }
        }
    }

    /**
     * @param $user
     * @param $amount
     * @return void
     */
    private function setupNewMember(User $user, $amount)
    {
        //At this point the day of the month hasn't been set for the user, set it now
        $user->updateSubscription('paypal', Carbon::now()->day);

        //if this is blank then the user hasn't been setup yet
        $subCharge = $this->subscriptionChargeRepository->createCharge($user->id, Carbon::now(), $amount);
    }
} 