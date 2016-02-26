<?php namespace BB\Http\Controllers;

use BB\Entities\User;
use BB\Exceptions\AuthenticationException;
use BB\Exceptions\ValidationException;
use BB\Repo\PaymentRepository;
use Illuminate\Http\Request;

class BalanceController extends Controller
{

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Services\Credit
     */
    private $bbCredit;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var \BB\Validators\WithdrawalValidator
     */
    private $withdrawalValidator;

    public function __construct(\BB\Repo\UserRepository $userRepository, \BB\Services\Credit $bbCredit, PaymentRepository $paymentRepository, \BB\Validators\WithdrawalValidator $withdrawalValidator)
    {
        $this->userRepository = $userRepository;
        $this->bbCredit = $bbCredit;
        $this->paymentRepository = $paymentRepository;
        $this->withdrawalValidator = $withdrawalValidator;
    }

    public function index($userId)
    {
        //Verify the user can access this user record
        $user = User::findWithPermission($userId);
        $this->bbCredit->setUserId($user->id);

        $userBalance = $this->bbCredit->getBalanceFormatted();
        $userBalanceSign = $this->bbCredit->getBalanceSign();

        $payments = $this->bbCredit->getBalancePaymentsPaginated();

        $memberList = $this->userRepository->getAllAsDropdown();

        return \View::make('account.bbcredit.index')
            ->with('user', $user)
            ->with('payments', $payments)
            ->with('userBalance', $userBalance)
            ->with('userBalanceSign', $userBalanceSign)
            ->with('rawBalance', number_format($user->cash_balance / 100, 2))
            ->with('memberList', $memberList);
    }

    public function withdrawal(Request $request, $userId)
    {
        $this->withdrawalValidator->validate(\Request::only(['amount', 'sort_code', 'account_number']));

        $user          = User::findWithPermission($userId);
        $amount        = $request->get('amount');
        $sortCode      = $request->get('sort_code');
        $accountNumber = $request->get('account_number');

        $memberName = $user->name;
        \Mail::queue('emails.withdrawal', [
            'memberName'    => $memberName,
            'amount'        => $amount,
            'sortCode'      => $sortCode,
            'accountNumber' => $accountNumber
        ], function ($message) {
            $message->to('trustees@buildbrighton.com', 'BB Trustees')->subject('User requested a withdrawal');
        });

        \Notification::success("Request sent");
        return \Redirect::route('account.balance.index', $user->id);

    }

    /**
     * This is a basic method for recording a payment transfer between two people
     * This should not exist and the normal balance payment controller should be used
     * If any more work is needed here please take the time and move it over!
     *
     * @param Request $request
     * @param integer $userId
     *
     * @return mixed
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function recordTransfer(Request $request, $userId)
    {
        $user = User::findWithPermission($userId);
        $this->bbCredit->setUserId($user->id);

        $amount       = $request->get('amount');
        $targetUserId = $request->get('target_user_id');
        $targetUser   = $this->userRepository->getById($targetUserId);

        if ($targetUserId === $userId) {
            throw new ValidationException('Your\'e trying to send money to yourself, no!');
        }

        //What is the users balance
        $userBalance = $this->bbCredit->getBalance();

        //With this payment will the users balance go to low?
        if (($userBalance - $amount) < 0) {

            \Notification::error("You don't have the money for this");
            return \Redirect::route('account.balance.index', $user->id);
        }

        $this->paymentRepository->recordBalanceTransfer($user->id, $targetUser->id, $amount);

        \Notification::success("Transfer made");
        return \Redirect::route('account.balance.index', $user->id);
    }

} 