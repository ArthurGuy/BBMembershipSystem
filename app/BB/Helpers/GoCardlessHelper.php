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
            'app_id'        => $_ENV['GOCARDLESS_APP_ID'],
            'app_secret'    => $_ENV['GOCARDLESS_APP_SECRET'],
            'merchant_id'   => $_ENV['GOCARDLESS_MERCHANT_ID'],
            'access_token'  => $_ENV['GOCARDLESS_ACCESS_TOKEN'],
        );

        // Initialize GoCardless
        if (\App::environment() == 'production')
        {
            //\GoCardless::$environment = 'production';
        }
        \GoCardless::set_account_details($this->account_details);
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
} 