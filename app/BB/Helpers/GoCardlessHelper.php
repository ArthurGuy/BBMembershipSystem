<?php namespace BB\Helpers;

class GoCardlessHelper {

    private $account_details;

    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        $this->account_details = array(
            'app_id'        => $_SERVER['GOCARDLESS_APP_ID'],
            'app_secret'    => $_SERVER['GOCARDLESS_APP_SECRET'],
            'merchant_id'   => $_SERVER['GOCARDLESS_MERCHANT_ID'],
            'access_token'  => $_SERVER['GOCARDLESS_ACCESS_TOKEN'],
        );

        // Initialize GoCardless
        if (\App::environment() == 'production')
        {
            \GoCardless::$environment = 'production';
        }
        \GoCardless::set_account_details($this->account_details);
    }

    public function newPreAuthUrl($paymentDetails)
    {
        $baseDetails = array(
            'max_amount'        => 100,
            'interval_length'   => 1,
            'interval_unit'     => 'month',
            //'name'              => 'BBSUB'.$user->id,
            //'description'       => 'Build Brighton Monthly Subscription',
        );
        $paymentDetails = array_merge($baseDetails, $paymentDetails);
        return \GoCardless::new_pre_authorization_url($paymentDetails);
    }

    public function newSubUrl($payment_details)
    {
        return \GoCardless::new_subscription_url($payment_details);
    }

    public function confirmResource($confirm_params)
    {
        return \GoCardless::confirm_resource($confirm_params);
    }

    public function cancelSubscription($id)
    {
        return \GoCardless_Subscription::find($id)->cancel();
    }

    public function validateWebhook($webhook_array)
    {
        return \GoCardless::validate_webhook($webhook_array);
    }

    public function newBillUrl($payment_details)
    {
        return \GoCardless::new_bill_url($payment_details);
    }

    public function getSubscriptionFirstBill($id)
    {
        $bills = \GoCardless_Merchant::find($this->account_details['merchant_id'])->bills(array('source_id' => $id));
        if (count($bills) > 0)
        {
            return $bills[0];
        }
        return false;
    }

    /**
     * Create a new payment against a preauth
     * @param      $preauthId
     * @param      $amount
     * @param null $name
     * @param null $description
     * @return bool
     */
    public function newBill($preauthId, $amount, $name = null, $description = null)
    {
        try {
            $preAuth = \GoCardless_PreAuthorization::find($preauthId);
            $details = [
                'amount'      => $amount,
                'name'        => $name,
                'description' => $description,
            ];
            return $preAuth->create_bill($details);
        } catch (\GoCardless_ApiException $e) {
            \Log::error($e);
            return false;
        }
    }

    public function cancelPreAuth($preauthId)
    {
        try {
            $preAuth = \GoCardless_PreAuthorization::find($preauthId);
            $preAuthStatus = $preAuth->cancel();
            return ($preAuthStatus->status == 'cancelled');
        } catch (\GoCardless_ApiException $e) {
            \Log::error($e);
            return false;
        }
    }
} 