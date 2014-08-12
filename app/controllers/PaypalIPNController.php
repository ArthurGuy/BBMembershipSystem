<?php

use BB\Helpers\PayPalConfig;

class PaypalIPNController extends \BaseController {

    public function receiveNotification()
    {
        $ipnMessage = new \PayPal\IPN\PPIPNMessage('', PayPalConfig::getConfig());

        foreach($ipnMessage->getRawData() as $key => $value) {
            //\Log::debug("IPN: $key => $value");
        }

        if($ipnMessage->validate()) {
            \Log::debug("IPN Success");
        } else {
            \Log::error("Invalid IPN");
        }

        $ipnData = $ipnMessage->getRawData();

        \Log::debug(json_encode($ipnData));

        if ($ipnData['txn_type'] == 'subscr_payment')
        {
            if ($ipnData['payment_status'] != 'Completed')
            {
                \Log::error("Received different payment status for sub payment. ".$ipnData['payment_status']);
                return;
            }
            $user = User::where('email', $ipnData['payer_email'])->first();
            if (!$user)
            {
                \Log::error("IPN Received for unknown email ".$ipnData['payer_email']);
                return;
            }
            Payment::create([
                'reason' => 'subscription',
                'source' => 'paypal',
                'source_id' => $ipnData['txn_id'],
                'user_id' => $user->id,
                'amount' => $ipnData['mc_gross'],
                'fee' => $ipnData['mc_fee'],
                'amount_minus_fee' => ($ipnData['mc_gross'] - $ipnData['mc_fee']),
                'status' => 'paid'
            ]);
            $date = new \Carbon\Carbon();
            $user->extendMembership('paypal', $date->addMonth());
        }
    }
} 