<?php

class BalancePaymentController extends \BaseController {


    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var \BB\Services\Credit
     */
    private $bbCredit;

    function __construct(\BB\Repo\PaymentRepository $paymentRepository, \BB\Services\Credit $bbCredit)
    {
        $this->paymentRepository = $paymentRepository;
        $this->bbCredit = $bbCredit;

        $this->beforeFilter('role:member', array('only' => ['store']));
    }


    /**
     * Start the creation of a new balance payment
     *   Details get posted into this method
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     */
	public function store($userId)
    {
        $user = User::findWithPermission($userId);
        $this->bbCredit->setUserId($user->id);

        $amount      = Request::get('amount');
        $reason      = Request::get('reason');
        $returnPath  = Request::get('return_path');
        $ref         = Request::get('ref');

        //Can the users balance go below 0
        $minimumBalance = $this->bbCredit->acceptableNegativeBalance($reason);

        //What is the users balance
        $userBalance = $this->bbCredit->getBalance();

        //With this payment will the users balance go to low?
        if (($userBalance - $amount) < $minimumBalance) {
            Notification::error("You don't have the money for this");
            return Redirect::to($returnPath);
        }

        //Everything looks gooc, create the payment
        $this->paymentRepository->recordPayment($reason, $userId, 'balance', '', $amount, 'paid', 0, $ref);

        //Update the users cached balance
        $this->bbCredit->recalculate();

        Notification::success("Payment made");
        return Redirect::to($returnPath);
    }

}
