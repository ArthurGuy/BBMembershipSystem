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
     * Subscribe to the payment events
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('sub-charge.paid', 'BB\Handlers\SubChargeEventHandler@onPaid');
        $events->listen('sub-charge.processing', 'BB\Handlers\SubChargeEventHandler@onProcessing');
        $events->listen('sub-charge.payment-failed', 'BB\Handlers\SubChargeEventHandler@onPaymentFailure');
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
            \Log::warning('Unable to update member expiry date - payment cancelled. User ID:' . $userId);
        }
    }

} 