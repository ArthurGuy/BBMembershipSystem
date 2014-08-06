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

        if ($webhook_valid == TRUE)
        {
            //print_r($webhook_array);

            $webhook_array['resource_type'];
            $webhook_array['action'];

            if ($webhook_array['resource_type'] == 'bill')
            {
                if ($webhook_array['action'] == 'created')
                {
                    //We have new bills/payments, don't do anything.
                    // The local records are created by the process that started it
                }
                elseif ($webhook_array['action'] == 'paid')
                {
                    //Bills have been paid, update the local status
                    foreach ($webhook_array['bills'] as $bill)
                    {
                        if ($bill['source_type'] == 'subscription')
                        {
                            $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                            if ($existingPayment)
                            {
                                $existingPayment->status = $bill['status'];
                                $existingPayment->save();
                            }
                            else
                            {
                                $payment = new Payment([
                                    'reason'            => 'subscription',
                                    'source'            => 'gocardless',
                                    'source_id'         => $bill['id'],
                                    'amount'            => $bill['amount'],
                                    'fee'               => $bill['amount'] - $bill['amount_minus_fees'],
                                    'amount_minus_fee'  => $bill['amount_minus_fees'],
                                    'status'            => $bill['status']
                                ]);
                                $user = User::where('payment_method', 'gocardless')->where('subscription_id', $bill['source_id'])->first();
                                if ($user)
                                {
                                    $user->payments()->save($payment);
                                }
                                else
                                {
                                    Log::warning("Gocardless Payment Created, can't identify owner. Bill ID:".$bill['id']);
                                }
                            }
                        }

                    }
                }
                else
                {

                    foreach ($webhook_array['bills'] as $bill)
                    {


                        if (($bill['status'] == 'failed') || ($bill['status'] == 'cancelled'))
                        {
                            //Payment failed or cancelled
                            //Roll back the payment date field
                            //last_subscription_payment
                            //Email the user, perhaps after a day in case they are canceling and restarting a payment


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
            elseif ($webhook_array['resource_type'] == 'pre_authorization')
            {
                foreach($webhook_array['pre_authorizations'] as $preAuth)
                {
                    if ($preAuth['status'] == 'cancelled')
                    {
                        //Preauths aren't used
                    }
                }
            }
            elseif ($webhook_array['resource_type'] == 'subscription')
            {
                foreach ($webhook_array['subscriptions'] as $sub)
                {
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
        else
        {
            return Response::make('', 403);
        }
    }

}