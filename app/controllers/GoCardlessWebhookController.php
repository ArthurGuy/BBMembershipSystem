<?php


use \Carbon\Carbon;

class GoCardlessWebhookController extends \BaseController {

    public function __construct(\BB\Helpers\GoCardlessHelper $goCardless)
    {
        $this->goCardless = $goCardless;
    }

    public function receive()
    {

        $request = Request::instance();
        $webhook = $request->getContent();
        $webhook_array = json_decode($webhook, true);
        $webhook_array = $webhook_array['payload'];
        $webhook_valid = $this->goCardless->validateWebhook($webhook_array);

        if ($webhook_valid == false) {
            return Response::make('', 403);
        }

        //print_r($webhook_array);

        $webhook_array['resource_type'];
        $webhook_array['action'];

        if ($webhook_array['resource_type'] == 'bill')
        {
            if ($webhook_array['action'] == 'created')
            {
                //We have new bills/payment
                foreach ($webhook_array['bills'] as $bill)
                {
                    if ($bill['source_type'] == 'subscription')
                    {
                        //This is a monthly subscription payment
                        //We will also receive this for the initial sub payment which we have recorded seperatly
                        $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                        if (!$existingPayment)
                        {
                            //Locate the user through their subscription id
                            $user = User::where('payment_method', 'gocardless')->where('subscription_id', $bill['source_id'])->first();
                            if ($user)
                            {
                                //Record their monthly payment
                                $payment = new Payment([
                                    'reason'            => 'subscription',
                                    'source'            => 'gocardless',
                                    'source_id'         => $bill['id'],
                                    'amount'            => $bill['amount'],
                                    'amount_minus_fee'  => $bill['amount_minus_fees'],
                                    'fee'               => ($bill['amount'] - $bill['amount_minus_fees']),
                                    'status'            => $bill['status']
                                ]);
                                $user->payments()->save($payment);
                                //Extend their monthly subscription
                                $user->extendMembership('gocardless', \Carbon\Carbon::now()->addMonth());
                            }
                        }
                    }
                }
            }
            elseif ($webhook_array['action'] == 'paid')
            {
                //We don't need to do anything for paid bills, a pending bill is treated as good so nothing new happens as this stage

                //Update the status of the local record
                foreach ($webhook_array['bills'] as $bill)
                {
                    $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                    if ($existingPayment)
                    {
                        $existingPayment->status = $bill['status'];
                        $existingPayment->save();
                    }
                    else
                    {
                        //Existing payment cant be found - payments we care about start in the system so this is alright
                    }
                }
            }
            else
            {

                foreach ($webhook_array['bills'] as $bill)
                {
                    $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                    if ($existingPayment)
                    {
                        //Start by updating the local record
                        $existingPayment->status = $bill['status'];
                        $existingPayment->save();
                        if (($bill['status'] == 'failed') || ($bill['status'] == 'cancelled'))
                        {
                            //Payment failed or cancelled - either way we don't have the money!
                            //We need to retrieve the payment from the user somehow but don't want to cancel the subscription.

                            if ($existingPayment->reason == 'subscription')
                            {
                                //If the payment is a subscription payment then we need to take action and warn the user
                                $user = $existingPayment->user()->first();
                                $user->status = 'payment-warning';

                                //Rollback the users subscription expiry date or set it to today
                                $expiryDate = \BB\Helpers\MembershipPayments::lastUserPaymentExpires($user->id);
                                if ($expiryDate) {
                                    $user->subscription_expires = $expiryDate;
                                } else {
                                    $user->subscription_expires = new Carbon();
                                }

                                $user->save();
                            }
                            elseif ($existingPayment->reason == 'induction')
                            {
                                //We still need to collect the payment from the user
                            }
                            elseif ($existingPayment->reason == 'box-deposit')
                            {

                            }
                            elseif ($existingPayment->reason == 'key-deposit')
                            {

                            }
                            //Email the user, perhaps after a day in case they are canceling and restarting a payment



                            //Roll back the payment date field
                            //last_subscription_payment



                        }
                        elseif ($bill['status'] == 'refunded')
                        {
                            //Payment refunded
                            //Update the payment record and possible the user record
                        }
                        elseif ($bill['status'] == 'withdrawn')
                        {
                            //Money taken out - not our concern
                        }
                    }
                }
            }
        }
        elseif ($webhook_array['resource_type'] == 'pre_authorization')
        {
            //Preauths aren't used
        }
        elseif ($webhook_array['resource_type'] == 'subscription')
        {
            foreach ($webhook_array['subscriptions'] as $sub)
            {
                //Setup messages aren't used as we deal with them directly.
                if ($sub['status'] == 'cancelled')
                {
                    //Make sure our local record is correct
                    $user = User::where('payment_method', 'gocardless')->where('subscription_id', $sub['id'])->first();
                    if ($user)
                    {
                        $user->cancelSubscription();
                    }
                }
            }
        }

        return Response::make('Success', 200);

    }

}