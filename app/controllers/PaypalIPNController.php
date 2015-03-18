<?php

use BB\Helpers\PayPalConfig;

class PaypalIPNController extends \BaseController
{

    /**
     * @var \BB\Repo\SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    function __construct(\BB\Repo\SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }

    public function receiveNotification()
    {
        $ipnMessage = new \PayPal\IPN\PPIPNMessage('', PayPalConfig::getConfig());

        if (!$ipnMessage->validate()) {
            \Log::error("Invalid IPN");
        }

        $ipnData = $ipnMessage->getRawData();

        //\Log::debug(json_encode($ipnData));

        if (isset($ipnData['txn_type']) && ($ipnData['txn_type'] == 'subscr_payment')) {
            if ($ipnData['payment_status'] != 'Completed') {
                \Log::error("PayPal IPN: Received unknown payment status for sub payment: \"" . $ipnData['payment_status']."\" Email: ".$ipnData['payer_email']);
                return;
            }
            $user = User::where('email', $ipnData['payer_email'])->first();
            if (!$user) {
                $user = User::where('secondary_email', $ipnData['payer_email'])->first();
            }
            if (!$user) {
                \Log::error("PayPal IPN Received for unknown email " . $ipnData['payer_email']);
                return;
            }

            $paymentDate = new \Carbon\Carbon();


            //See if there is a subscription charge the user needs to pay for
            $ref = null;
            $subCharge = $this->subscriptionChargeRepository->findCharge($user->id, $paymentDate);
            if ($subCharge) {
                $ref = $subCharge->id;
                if ($subCharge->amount == $ipnData['mc_gross']) {
                    $this->subscriptionChargeRepository->markChargeAsPaid($subCharge->id, $paymentDate);
                } else {
                    //@TODO: Handle partial payments
                }
            }

            Payment::create(
                [
                    'reason'           => 'subscription',
                    'source'           => 'paypal',
                    'source_id'        => $ipnData['txn_id'],
                    'user_id'          => $user->id,
                    'amount'           => $ipnData['mc_gross'],
                    'fee'              => $ipnData['mc_fee'],
                    'amount_minus_fee' => ($ipnData['mc_gross'] - $ipnData['mc_fee']),
                    'status'           => 'paid',
                    'reference'        => $ref,
                ]
            );

            $user->extendMembership('paypal', $paymentDate->addMonth());
        } elseif (isset($ipnData['txn_type']) && ($ipnData['txn_type'] == 'subscr_cancel')) {
            $user = User::where('email', $ipnData['payer_email'])->first();
            if ($user) {
                //The user may have already changed to another method, only cancel if its still paypal
                if ($user->payment_method == 'paypal') {
                    $user->cancelSubscription();
                }
            }
        }
    }
} 