<?php namespace BB\Http\Controllers;


use BB\Entities\Payment;
use BB\Entities\User;
use BB\Helpers\GoCardlessHelper;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use \Carbon\Carbon;
use Illuminate\Http\Request;

class GoCardlessWebhookController extends Controller
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
        $request = \Request::instance();
        $webhookData = $request->getContent();
        $signature = $request->header('Webhook-Signature');

        $hash = hash_hmac('sha256', $webhookData, env('NEW_GOCARDLESS_WEBHOOK_SECRET'));

        if ($signature != $hash) {
            return \Response::make('', 403);
        }

        $webhookData = json_decode($webhookData, true);

        foreach ($webhookData['events'] as $event) {
            $parser = new \BB\Services\Payment\GoCardlessWebhookParser();
            $parser->parseResponse($event);

            switch ($parser->getResourceType()) {
                case 'payments':

                    switch ($parser->getAction()) {
                        case 'created':

                            $this->processNewPayment($event);

                            break;
                        case 'submitted':

                            break;
                        case 'confirmed':

                            $this->processPaidBills($event);

                            break;
                        case 'paid_out':

                            break;
                        case 'failed':
                        case 'cancelled':

                            $this->paymentFailed($event);

                            break;
                        default:

                            \Log::info('GoCardless payment event. Action: ' . $parser->getAction() . '. Data: ' . json_encode($event));
                    }

                    break;
                case 'mandates':

                    switch ($parser->getAction()) {
                        case 'cancelled':

                            $this->cancelPreAuth($event);

                            break;
                        default:
                    }

                    break;
                case 'subscriptions':

                    switch ($parser->getAction()) {
                        case 'cancelled':

                            $this->cancelSubscriptions($event);

                            break;
                        case 'payment_created':

                            $this->processNewSubscriptionPayment($event);

                            break;
                        default:

                            \Log::info('GoCardless subscription event. Action: ' . $parser->getAction() . '. Data: ' . json_encode($event));
                    }

                    break;
            }
        }

        return \Response::make('Success', 200);
    }


    /**
     * A Bill has been created, these will always start within the system except for subscription payments
     *
     * @param array $bill
     */
    private function processNewPayment(array $bill)
    {
        \Log::info('New payment notification. ' . json_encode($bill));
    }


    /**
     * A Bill has been created, these will always start within the system except for subscription payments
     *
     * @param array $bill
     */
    private function processNewSubscriptionPayment(array $bill)
    {
        // Lookup the payment from the API
        $payment = $this->goCardless->getPayment($bill['links']['payment']);

        try {

            //Locate the user through their subscription id
            $user = User::where('payment_method', 'gocardless')->where('subscription_id', $bill['links']['subscription'])->first();

            if ( ! $user) {
                \Log::warning("GoCardless new sub payment notification for unmatched user. Bill ID: " . $bill['links']['payment']);

                return;
            }

            $amount = ($payment->amount * 1) / 100;
            $this->paymentRepository->recordSubscriptionPayment($user->id, 'gocardless', $bill['links']['payment'], $amount, $payment->status);

        } catch (\Exception $e) {
            \Log::error($e);
        }
    }


    private function processPaidBills(array $bill)
    {
        //When a bill is paid update the status on the local record and the connected sub charge (if there is one)

        $existingPayment = $this->paymentRepository->getPaymentBySourceId($bill['links']['payment']);
        if ($existingPayment) {

            if (isset($bill['paid_at'])) {
                $paymentDate = new Carbon($bill['paid_at']);
            } else {
                $paymentDate = new Carbon();
            }

            $this->paymentRepository->markPaymentPaid($existingPayment->id, $paymentDate);

        } else {
            \Log::info("GoCardless Webhook received for unknown payment: " . $bill['id']);
        }
    }

    /**
     * @param array $bill
     */
    private function paymentFailed(array $bill)
    {
        $existingPayment = $this->paymentRepository->getPaymentBySourceId($bill['links']['payment']);
        $payment = $this->goCardless->getPayment($bill['links']['payment']);
        if ($existingPayment) {
            $this->paymentRepository->recordPaymentFailure($existingPayment->id, $payment->status);
        } else {
            \Log::info("GoCardless Webhook received for unknown payment: " . $bill['links']['payment']);
        }

    }

    /**
     * @param string $action
     */
    private function processBills($action, array $bills)
    {
        foreach ($bills as $bill) {
            $existingPayment = $this->paymentRepository->getPaymentBySourceId($bill['id']);
            if ($existingPayment) {
                if (($bill['status'] == 'pending') && ($action == 'resubmission_requested')) {
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
                }
            } else {
                \Log::info("GoCardless Webhook received for unknown payment: " . $bill['id']);
            }
        }

    }

    /**
     * @param array $preAuth
     */
    private function cancelPreAuth($preAuth)
    {
        /** @var User $user */
        $user = User::where('payment_method', 'gocardless-variable')->where('subscription_id', $preAuth['links']['mandate'])->first();
        if ($user) {
            $user->cancelSubscription();
        }
    }


    private function cancelSubscriptions($subscription)
    {
        //Make sure our local record is correct
        /** @var User $user */
        $user = User::where('payment_method', 'gocardless')->where('subscription_id', $subscription['links']['subscription'])->first();
        if ($user) {
            $user->cancelSubscription();
        }
    }

}
