<?php namespace BB\Services;

use BB\Repo\PaymentRepository;
use BB\Repo\UserRepository;

class Credit {

    private $userId;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param PaymentRepository $paymentRepository
     * @param UserRepository    $userRepository
     */
    public function __construct(PaymentRepository $paymentRepository, UserRepository $userRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


    public function recalculate()
    {
        $runningTotal = 0;
        $positivePayments = $this->paymentRepository->getUserPaymentsByReason($this->userId, 'balance');
        $negativePayments = $this->paymentRepository->getUserPaymentsBySource($this->userId, 'balance');

        foreach ($positivePayments as $payment) {
            $runningTotal = $runningTotal + ($payment->amount * 100);
        }
        foreach ($negativePayments as $payment) {
            $runningTotal = $runningTotal - ($payment->amount * 100);
        }
        $user = $this->userRepository->getById($this->userId);
        $user->cash_balance = $runningTotal;
        $user->save();
    }


} 