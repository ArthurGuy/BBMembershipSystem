<?php namespace BB\Helpers;

class GoCardlessHelper
{

    private $account_details;

    /**
     * @var \GoCardlessPro\Client
     */
    private $client;

    public function __construct()
    {
        $this->setup();
    }

    public function setup()
    {
        $this->account_details = array(
            'app_id'        => env('GOCARDLESS_APP_ID', ''),
            'app_secret'    => env('GOCARDLESS_APP_SECRET', ''),
            'merchant_id'   => env('GOCARDLESS_MERCHANT_ID', ''),
            'access_token'  => env('GOCARDLESS_ACCESS_TOKEN', ''),
        );

        $this->client = new \GoCardlessPro\Client([
            'access_token' => env('NEW_GOCARDLESS_ACCESS_TOKEN'),
            'environment' => (env('NEW_GOCARDLESS_ENV', 'LIVE') == 'LIVE')? \GoCardlessPro\Environment::LIVE: \GoCardlessPro\Environment::SANDBOX,
        ]);
    }

    public function getPayment($paymentId)
    {
        return $this->client->payments()->get($paymentId);
    }

    public function newPreAuthUrl($user, $paymentDetails)
    {
        $redirectFlow = $this->client->redirectFlows()->create([
            "params" => $paymentDetails
        ]);

        $user->gocardless_setup_id = $redirectFlow->id;
        $user->save();

        return $redirectFlow->redirect_url;
    }

    public function confirmResource($user, $confirm_params)
    {
        return $this->client->redirectFlows()->complete(
            $user->gocardless_setup_id,
            ["params" => ["session_token" => 'user-token-'.$user->id]]
        );
    }


    public function createSubscription($mandate, $amount, $dayOfMonth, $subscriptionNumber)
    {
        $subscription = $this->client->subscriptions()->create([
            "params" => [
                "amount"        => $amount, // GBP in pence
                "currency"      => "GBP",
                "interval_unit" => "monthly",
                "day_of_month"  => $dayOfMonth,
                "links"         => [
                    "mandate" => $mandate
                ],
                "metadata"      => [
                    "subscription_number" => $subscriptionNumber
                ]
            ],
        ]);

        return $subscription;
    }
    public function cancelSubscription($id)
    {
        return $this->client->subscriptions()->cancel($id);
    }

    /**
     * Create a new payment against a preauth
     * @param             $mandateId
     * @param             $amount
     * @param null|string $name
     * @param null|string $description
     * @return bool|mixed
     */
    public function newBill($mandateId, $amount, $name = null, $description = null)
    {
        // If the total is above £50 something probably isn't right
        if ($amount > (50 * 100)) {
            throw new \Exception("Attempting a DD charge for over £50");
        }
        try {
            return $this->client->payments()->create([
                "params" => [
                    "amount" => $amount, // amount in pence
                    "currency" => "GBP",
                    "links" => [
                        "mandate" => $mandateId
                    ],
                    "metadata" => [
                        "description" => $name
                    ]
                ],
                "headers" => [
                    //"Idempotency-Key" => $preauthId . ':' . time()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return false;
        }
    }

    public function cancelPreAuth($preauthId)
    {
        if (empty($preauthId)) {
            return true;
        }
        try {
            $mandate = $this->client->mandates()->cancel($preauthId);

            if ($mandate->status == 'cancelled') {
                return true;
            }

            \Log::error('Canceling pre auth failed: ' . json_encode($mandate));
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return false;
    }

    /**
     * @param string $reason
     * @return null|string
     */
    public function getNameFromReason($reason)
    {
        switch ($reason) {
            case 'subscription':
                return 'Monthly subscription';
            case 'balance':
                return 'Balance top up';
            case 'equipment-fee':
                return 'Equipment access fee';
            case 'induction':
                return 'Equipment induction';
            case 'door-key':
                return 'Door key';
        }

        return $reason;
    }
} 
