<?php namespace BB\Http\Controllers;

use BB\Entities\User;

class BalancePaymentController extends Controller
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

        $this->middleware('role:member', array('only' => ['store']));
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

        $requestData = \Request::only(['reason', 'amount', 'return_path', 'ref']);

        $amount      = ($requestData['amount'] * 1) / 100;
        $reason      = $requestData['reason'];
        $returnPath  = $requestData['return_path'];
        $ref         = $requestData['ref'] ?: '';

        //Can the users balance go below 0
        $minimumBalance = $this->bbCredit->acceptableNegativeBalance($reason);

        //What is the users balance
        $userBalance = $this->bbCredit->getBalance();

        //With this payment will the users balance go to low?
        if (($userBalance - $amount) < -$minimumBalance) {

            if (\Request::wantsJson()) {
                return \Response::json(['error' => 'You don\'t have the money for this'], 400);
            }

            \Notification::error("You don't have the money for this");
            return \Redirect::to($returnPath);
        }

        //Everything looks good, create the payment
        $this->paymentRepository->recordPayment($reason, $userId, 'balance', '', $amount, 'paid', 0, $ref);

        //Update the users cached balance
        $this->bbCredit->recalculate();

        if (\Request::wantsJson()) {
            return \Response::json(['message' => 'Payment made']);
        }

        \Notification::success("Payment made");
        return \Redirect::to($returnPath);
    }

}
