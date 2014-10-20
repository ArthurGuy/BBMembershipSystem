<?php

class StripePaymentController extends \BaseController {


    /**
     * @var \BB\Repo\EquipmentRepository
     */
    private $equipmentRepository;
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
        $user = User::findWithPermission($userId);

        $stripeToken = Request::get('stripe_token');
        $amount = Request::get('amount');
        $reason = Request::get('reason');
        $redirect_url = Request::get('redirect_url');

        try {
            $charge = Stripe_Charge::create(
                array(
                    "amount"      => $amount * 100,
                    "currency"    => "gbp",
                    "card"        => $stripeToken,
                    "description" => $reason
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Notification::error("There was an error confirming your payment");
            return Redirect::to($redirect_url);
        }

        //Replace the amount with the one from the charge, this prevents issues with variable tempering
        $amount = ($charge->amount / 100);

        //Stripe don't provide us with the fee so this should be OK
        $fee = (($amount * 0.024) + 0.2);

        $this->paymentRepository->recordPayment($reason, $userId, 'stripe', $charge->id, $amount, 'paid', $fee);

        Notification::success("Payment made");
        return Redirect::to($redirect_url);
    }

}
