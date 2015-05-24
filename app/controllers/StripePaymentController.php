<?php

use BB\Entities\User;

class StripePaymentController extends \BaseController
{


    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    function __construct(\BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;

        $this->beforeFilter('role:member', array('only' => ['store']));
    }


    /**
     * Start the creation of a new gocardless payment
     *   Details get posted into this method and the redirected to gocardless
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     */
    public function store($userId)
    {
        User::findWithPermission($userId);

        $requestData = Request::only(['reason', 'amount', 'return_path', 'stripeToken', 'ref']);

        $stripeToken = $requestData['stripeToken'];
        $amount      = $requestData['amount'];
        $reason      = $requestData['reason'];
        $returnPath  = $requestData['return_path'];
        $ref         = $requestData['ref'];

        try {
            $charge = Stripe_Charge::create(
                array(
                    "amount"      => $amount,
                    "currency"    => "gbp",
                    "card"        => $stripeToken,
                    "description" => $reason
                )
            );
        } catch (\Exception $e) {
            Log::error($e);

            if (Request::wantsJson()) {
                return Response::json(['error' => 'There was an error confirming your payment'], 400);
            }

            Notification::error("There was an error confirming your payment");
            return Redirect::to($returnPath);
        }

        //Replace the amount with the one from the charge, this prevents issues with variable tempering
        $amount = ($charge->amount / 100);

        //Stripe don't provide us with the fee so this should be OK
        $fee = (($amount * 0.024) + 0.2);

        $this->paymentRepository->recordPayment($reason, $userId, 'stripe', $charge->id, $amount, 'paid', $fee, $ref);

        if (Request::wantsJson()) {
            return Response::json(['message' => 'Payment made']);
        }

        Notification::success("Payment made");
        return Redirect::to($returnPath);
    }

}
