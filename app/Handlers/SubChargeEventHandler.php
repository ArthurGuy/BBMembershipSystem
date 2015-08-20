<?php namespace BB\Handlers;

use BB\Helpers\MembershipPayments;
use BB\Repo\SubscriptionChargeRepository;
use BB\Repo\UserRepository;
use Carbon\Carbon;

class SubChargeEventHandler
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    /**
     * @param UserRepository               $userRepository
     * @param SubscriptionChargeRepository $subscriptionChargeRepository
     */
    public function __construct(UserRepository $userRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }


    /**
     * A subscription charge has been marked as paid
     *
     * @param integer $chargeId
     * @param integer $userId
     * @param Carbon  $paymentDate
     * @param double  $amount
     */
    public function onPaid($chargeId, $userId, Carbon $paymentDate, $amount)
    {
        $user = $this->userRepository->getById($userId);
        /** @var $user \BB\Entities\User */

        $user->extendMembership(null, $paymentDate->addMonth());
    }

    /**
     * A subscription charge has been marked as processing
     *
     * @param integer $chargeId
     * @param integer $userId
     * @param Carbon  $paymentDate
     * @param double  $amount
     */
    public function onProcessing($chargeId, $userId, Carbon $paymentDate, $amount)
    {
        $user = $this->userRepository->getById($userId);
        /** @var $user \BB\Entities\User */

        $user->extendMembership(null, $paymentDate->addMonth());
    }

    /**
     * A sub charge has been rolled back as a payment failed
     *
     * @param integer $chargeId
     * @param integer $userId
     * @param Carbon  $paymentDate
     * @param double  $amount
     */
    public function onPaymentFailure($chargeId, $userId, Carbon $paymentDate, $amount)
    {
        $paidUntil = MembershipPayments::lastUserPaymentExpires($userId);
        if ($paidUntil) {
            $user = $this->userRepository->getById($userId);
            /** @var $user \BB\Entities\User */
            $user->extendMembership(null, $paidUntil);
        } else {
            \Log::info('Payment cancelled, expiry date rollback failed as there is no previous payment. User ID:' . $userId);
        }
    }

} 