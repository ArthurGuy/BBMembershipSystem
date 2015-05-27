<?php

use BB\Entities\User;

class CashPaymentController extends \BaseController
{


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

        $this->beforeFilter('role:admin', array('only' => ['store', 'destroy']));
    }


    /**
     * Start the creation of a new gocardless payment
     *   Details get posted into this method and the redirected to gocardless
     *
     * @param $userId
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\FormValidationException
     * @throws \BB\Exceptions\NotImplementedException
     */
    public function store($userId)
    {
        User::findWithPermission($userId);

        $amount     = Request::get('amount');
        $reason     = Request::get('reason');
        $returnPath = Request::get('return_path');

        $this->paymentRepository->recordPayment($reason, $userId, 'cash', '', $amount);

        Notification::success("Payment recorded");

        return Redirect::to($returnPath);
    }

    /**
     * Remove cash from the users balance
     *
     * @param $userId
     * @return mixed
     * @throws \BB\Exceptions\AuthenticationException
     * @throws \BB\Exceptions\InvalidDataException
     */
    public function destroy($userId)
    {
        $user = User::findWithPermission($userId);
        $this->bbCredit->setUserId($userId);

        $amount     = Request::get('amount');
        $returnPath = Request::get('return_path');
        $ref = Request::get('ref');

        $minimumBalance = $this->bbCredit->acceptableNegativeBalance('withdrawal');

        if (($user->cash_balance + ($minimumBalance * 100)) < ($amount * 100)) {
            Notification::error("Not enough money");
            return Redirect::to($returnPath);
        }

        $this->paymentRepository->recordPayment('withdrawal', $userId, 'balance', '', $amount, 'paid', 0, $ref);

        $this->bbCredit->recalculate();

        Notification::success("Payment recorded");

        return Redirect::to($returnPath);
    }
}
