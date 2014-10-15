<?php 

class BBCreditController extends \BaseController {

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepository;
    /**
     * @var \BB\Repo\PaymentRepository
     */
    private $paymentRepository;

    public function __construct(\BB\Repo\UserRepository $userRepository, \BB\Repo\PaymentRepository $paymentRepository)
    {
        $this->userRepository = $userRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index($userId)
    {
        //Verify the user can access this user record
        $user = User::findWithPermission($userId);

        $payments = $this->paymentRepository->getBalancePaymentsPaginated($userId);

        return View::make('account.bbcredit.index')->with('user', $user)->with('payments', $payments);
    }

} 