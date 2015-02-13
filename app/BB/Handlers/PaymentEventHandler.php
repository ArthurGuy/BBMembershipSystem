<?php namespace BB\Handlers;

use BB\Repo\UserRepository;

class PaymentEventHandler {

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Subscribe to the payment events
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('payment.create', 'BB\Handlers\PaymentEventHandler@onCreate');
        $events->listen('payment.delete', 'BB\Handlers\PaymentEventHandler@onDelete');
    }


    /**
     * New payment record is created
     * @param $userId
     * @param $reason
     * @param $ref
     * @param $paymentId
     */
    public function onCreate($userId, $reason, $ref, $paymentId)
    {
        if ($reason == 'balance') {
            $this->updateBalance($userId);
        } elseif ($reason == 'subscription') {
            $this->confirmUserSubscription($userId);
        } elseif ($reason == 'induction') {
            $this->createInductionRecord($userId, $ref, $paymentId);
        } elseif ($reason == 'door-key') {
            $this->recordDoorKeyPaymentId($userId, $paymentId);
        } elseif ($reason == 'storage-box') {

        } elseif ($reason == 'equipment-fee') {
            $this->updateBalance($userId);
        } else {
            
        }
    }

    /**
     * @param $userId
     * @param $source
     */
    public function onDelete($userId, $source)
    {
        if ($source == 'balance') {
            $this->updateBalance($userId);
        }
    }


    private function updateBalance($userId)
    {
        $memberCreditService = \App::make('\BB\Services\Credit');
        $memberCreditService->setUserId($userId);
        $memberCreditService->recalculate();
    }

    private function confirmUserSubscription($userId)
    {
        $user = $this->userRepository->getById($userId);
        $user->status = 'active';
        $user->active = true;
        $user->save();
    }

    private function createInductionRecord($userId, $ref, $paymentId)
    {
        /* @TODO: Replace with a repo */
        /* @TODO: Verify payment amount is valid - this could have been changed */
        \Induction::create([
            'user_id' => $userId,
            'key' => $ref,
            'paid' => true,
            'payment_id' => $paymentId
        ]);
    }

    private function recordDoorKeyPaymentId($userId, $paymentId)
    {
        /* @TODO: Verify payment amount is valid - this could have been changed */
        $user = $this->userRepository->getById($userId);
        $user->key_deposit_payment_id = $paymentId;
        $user->save();
    }


} 