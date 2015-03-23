<?php


use BB\Helpers\GoCardlessHelper;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use \Carbon\Carbon;

class GoCardlessWebhookController extends \BaseController {

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    public function __construct(GoCardlessHelper $goCardless, PaymentRepository $paymentRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->goCardless = $goCardless;
        $this->paymentRepository = $paymentRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
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

        if ($webhook_array['resource_type'] == 'bill')
        {
            $this->processBills($webhook_array['action'], $webhook_array['bills']);
        }
        elseif ($webhook_array['resource_type'] == 'pre_authorization')
        {
            $this->processPreAuths();
        }
        elseif ($webhook_array['resource_type'] == 'subscription')
        {
            $this->processSubscriptions($webhook_array['subscriptions']);
        }

        return Response::make('Success', 200);

    }

    private function processBills($action, array $bills)
    {
        if ($action == 'created')
        {
            //We have new bills/payment
            foreach ($bills as $bill)
            {
                $paymentDate = new \Carbon\Carbon();
                try {
                    if ($bill['source_type'] == 'subscription') {
                        //This is a monthly subscription payment
                        //We will also receive this for the initial sub payment which we have recorded seperatly
                        $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                        if (!$existingPayment) {
                            //Locate the user through their subscription id
                            $user = User::where('payment_method', 'gocardless')->where('subscription_id', $bill['source_id'])->first();
                            if ($user) {
                                //Record their monthly payment
                                $fee = ($bill['amount'] - $bill['amount_minus_fees']);

                                $ref = null;

                                $subCharge = $this->subscriptionChargeRepository->findCharge($user->id, $paymentDate);
                                if ($subCharge) {
                                    $ref = $subCharge->id;
                                    if ($subCharge->amount == $bill['amount']) {
                                        $this->subscriptionChargeRepository->markChargeAsPaid($subCharge->id, $paymentDate);
                                    } else {
                                        //@TODO: Handle partial payments
                                        \Log::debug("Sub charge handling - gocardless partial payment");
                                    }
                                }

                                $this->paymentRepository->recordSubscriptionPayment($user->id, 'gocardless', $bill['id'], $bill['amount'], $bill['status'], $fee, $ref);

                                //Extend their monthly subscription
                                $user->extendMembership('gocardless', \Carbon\Carbon::now()->addMonth());
                            } else {
                                //Payment received but we cant match the user
                                \Log::error("GoCardless Payment notification for unmatched user. Bill ID: ".$bill['id']);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error($e);
                }
            }
        }
        elseif ($action == 'paid')
        {
            //We don't need to do anything for paid bills, a pending bill is treated as good so nothing new happens as this stage

            //Update the status of the local record
            foreach ($bills as $bill)
            {
                $existingPayment = Payment::where('source', 'gocardless')->where('source_id', $bill['id'])->first();
                if ($existingPayment)
                {
                    $existingPayment->status = $bill['status'];
                    $existingPayment->save();

                    //We need to locate the sub charge and rollback its status


                }
                else
                {
                    //Existing payment cant be found - payments we care about start in the system so this is alright
                }
            }
        }
        else
        {

            foreach ($bills as $bill)
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

    private function processPreAuths()
    {
        //Preauths are handled at creation
        //@TODO: we probably need to catch cancellations here
    }

    private function processSubscriptions($subscriptions)
    {
        foreach ($subscriptions as $sub)
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

}