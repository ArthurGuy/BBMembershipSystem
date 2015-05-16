<?php


use BB\Entities\Payment;
use BB\Entities\User;
use BB\Exceptions\PaymentException;
use BB\Helpers\GoCardlessHelper;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use \Carbon\Carbon;

class GoCardlessWebhookController extends \BaseController
{

    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;
    /**
     * @var GoCardlessHelper
     */
    private $goCardless;

    public function __construct(GoCardlessHelper $goCardless, PaymentRepository $paymentRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->goCardless = $goCardless;
        $this->paymentRepository = $paymentRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }

    public function receive()
    {
        $request = Request::instance();

        if ( ! $this->goCardless->validateWebhook($request->getContent())) {
            return Response::make('', 403);
        }

        $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
        $parser->parseResponse($request->getContent());

        switch ($parser->getResourceType()) {
            case 'bill':

                switch ($parser->getAction()) {
                    case 'created':

                        $this->processNewBills($parser->getBills());

                        break;
                    case 'paid':

                        $this->processPaidBills($parser->getBills());

                        break;
                    default:

                        $this->processBills($parser->getAction(), $parser->getBills());
                }

                break;
            case 'pre_authorization':

                    $this->processPreAuths($parser->getAction(), $parser->getPreAuthList());

                break;
            case 'subscription':

                    $this->processSubscriptions($parser->getSubscriptions());

                break;
        }

        return Response::make('Success', 200);
    }


    /**
     * A Bill has been created, these will always start within the system except for subscription payments
     *
     * @param array $bills
     */
    private function processNewBills(array $bills)
    {
        //We have new bills/payment
        foreach ($bills as $bill) {
            //Ignore non subscription payment creations
            if ($bill['source_type'] != 'subscription') {
                break;
            }
            try {

                //Locate the user through their subscription id
                $user = User::where('payment_method', 'gocardless')->where('subscription_id', $bill['source_id'])->first();

                if ( ! $user) {
                    Log::warning("GoCardless new sub payment notification for unmatched user. Bill ID: " . $bill['id']);

                    break;
                }

                $ref = null;

                $this->paymentRepository->recordSubscriptionPayment($user->id, 'gocardless', $bill['id'], $bill['amount'], $bill['status'], ($bill['amount'] - $bill['amount_minus_fees']), $ref);


            } catch (\Exception $e) {
                \Log::error($e);
            }
        }
    }


    private function processPaidBills(array $bills)
    {
        //When a bill is paid update the status on the local record and the connected sub charge (if there is one)

        foreach ($bills as $bill) {

            $existingPayment = $this->paymentRepository->getPaymentBySourceId($bill['id']);
            if ($existingPayment) {

                if ($bill['paid_at']) {
                    $paymentDate = new Carbon($bill['paid_at']);
                } else {
                    $paymentDate = new Carbon();
                }

                $this->paymentRepository->markPaymentPaid($existingPayment->id, $paymentDate);

            } else {
                Log::info("GoCardless Webhook received for unknown payment: " . $bill['id']);
            }
        }
    }

    private function processBills($action, array $bills)
    {
        foreach ($bills as $bill) {
            $existingPayment = $this->paymentRepository->getPaymentBySourceId($bill['id']);
            if ($existingPayment) {
                if (($bill['status'] == 'failed') || ($bill['status'] == 'cancelled')) {
                    //Payment failed or cancelled - either way we don't have the money!
                    //We need to retrieve the payment from the user somehow but don't want to cancel the subscription.
                    //$this->handleFailedCancelledBill($existingPayment);

                    $this->paymentRepository->recordPaymentFailure($existingPayment->id, $bill['status']);

                } elseif (($bill['status'] == 'pending') && ($action == 'retried')) {
                    //Failed payment is being retried
                    $subCharge = $this->subscriptionChargeRepository->getById($existingPayment->reference);
                    if ($subCharge) {
                        if ($subCharge->amount == $bill['amount']) {
                            $this->subscriptionChargeRepository->markChargeAsProcessing($subCharge->id);
                        } else {
                            //@TODO: Handle partial payments
                            \Log::warning("Sub charge handling - gocardless partial payment");
                        }
                    }
                } elseif ($bill['status'] == 'refunded') {
                    //Payment refunded
                    //Update the payment record and possible the user record
                } elseif ($bill['status'] == 'withdrawn') {
                    //Money taken out - not our concern
                }
            } else {
                Log::info("GoCardless Webhook received for unknown payment: " . $bill['id']);
            }
        }

    }

    private function processPreAuths($action, $preAuthList)
    {
        //Preauths are handled at creation
        foreach ($preAuthList as $preAuth) {
            if ($preAuth['status'] == 'cancelled') {
                $user = User::where('payment_method', 'gocardless')->where('subscription_id', $preAuth['id'])->first();
                if ($user) {
                    $user->cancelSubscription();
                }
            }
        }
    }


    private function processSubscriptions($subscriptions)
    {
        foreach ($subscriptions as $sub) {
            //Setup messages aren't used as we deal with them directly.
            if ($sub['status'] == 'cancelled') {
                //Make sure our local record is correct
                $user = User::where('payment_method', 'gocardless')->where('subscription_id', $sub['id'])->first();
                if ($user) {
                    $user->cancelSubscription();
                }
            }
        }
    }

    /**
     * The bill has been cancelled or failed, update the user records to compensate
     *
     * @param $existingPayment
     */
    private function handleFailedCancelledBill(Payment $existingPayment)
    {
        if ($existingPayment->reason == 'subscription') {
            //If the payment is a subscription payment then we need to take action and warn the user
            $user         = $existingPayment->user()->first();
            $user->status = 'suspended';

            //Rollback the users subscription expiry date or set it to today
            $expiryDate = \BB\Helpers\MembershipPayments::lastUserPaymentExpires($user->id);
            if ($expiryDate) {
                $user->subscription_expires = $expiryDate;
            } else {
                $user->subscription_expires = new Carbon();
            }

            $user->save();

            //Update the subscription charge to reflect the payment failure
            $subCharge = $this->subscriptionChargeRepository->getById($existingPayment->reference);
            if ($subCharge) {
                $this->subscriptionChargeRepository->paymentFailed($subCharge->id);
            }

        } elseif ($existingPayment->reason == 'induction') {
            //We still need to collect the payment from the user
        } elseif ($existingPayment->reason == 'box-deposit') {

        } elseif ($existingPayment->reason == 'key-deposit') {

        }
    }

}