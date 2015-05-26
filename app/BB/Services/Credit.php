<?php namespace BB\Services;

use BB\Entities\User;
use BB\Exceptions\InvalidDataException;
use BB\Exceptions\NotImplementedException;
use BB\Repo\PaymentRepository;
use BB\Repo\UserRepository;

class Credit
{

    private $userId;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $user;

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
        $this->user = $this->userRepository->getById($this->userId);
    }


    public function recalculate()
    {
        if (! $this->user instanceof User) {
            throw new InvalidDataException("User not set");
        }
        $runningTotal = 0;
        $positivePayments = $this->paymentRepository->getUserPaymentsByReason($this->userId, 'balance');
        $negativePayments = $this->paymentRepository->getUserPaymentsBySource($this->userId, 'balance');

        foreach ($positivePayments as $payment) {
            $runningTotal = $runningTotal + ($payment->amount * 100);
        }
        foreach ($negativePayments as $payment) {
            $runningTotal = $runningTotal - ($payment->amount * 100);
        }
        $this->user->cash_balance = $runningTotal;
        $this->user->save();
    }

    /**
     * Can the user spend money they don't have and if so how much?
     * @param $reason
     * @return int
     */
    public function acceptableNegativeBalance($reason)
    {
        switch ($reason) {
            case 'storage-box':
                return 0;
            case 'subscription':
                return 0;
            case 'induction':
                return 0;
            case 'equipment-fee':
                return 5;
            default:
                return 0;
        }
    }

    /**
     * Get the users balance
     * @return float
     */
    public function getBalance()
    {
        return $this->user->cash_balance / 100;
    }

    public function getBalanceFormatted()
    {
        return '&pound;' . number_format(($this->user->cash_balance / 100), 2);
    }

    public function getBalancePaymentsPaginated()
    {
        return $this->paymentRepository->getBalancePaymentsPaginated($this->user->id);
    }


    public static function getDeviceFee($device)
    {
        switch ($device) {
            case 'laser':
                return 3.00;
        }
        throw new NotImplementedException('No fee exists for this device');
    }

} 