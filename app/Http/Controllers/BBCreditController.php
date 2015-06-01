<?php namespace BB\Http\Controllers;

use BB\Entities\User;

class BBCreditController extends Controller
{

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Services\Credit
     */
    private $bbCredit;

    public function __construct(\BB\Repo\UserRepository $userRepository, \BB\Services\Credit $bbCredit)
    {
        $this->userRepository = $userRepository;
        $this->bbCredit = $bbCredit;
    }

    public function index($userId)
    {
        //Verify the user can access this user record
        $user = User::findWithPermission($userId);
        $this->bbCredit->setUserId($user->id);

        $userBalance = $this->bbCredit->getBalanceFormatted();

        $payments = $this->bbCredit->getBalancePaymentsPaginated();

        return \View::make('account.bbcredit.index')->with('user', $user)->with('payments', $payments)->with('userBalance', $userBalance);
    }

} 