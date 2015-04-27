<?php namespace BB\Handlers;

use BB\Entities\Induction;
use BB\Exceptions\PaymentException;
use BB\Repo\InductionRepository;
use BB\Repo\PaymentRepository;
use BB\Repo\SubscriptionChargeRepository;
use BB\Repo\UserRepository;

class PaymentEventHandler {

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var InductionRepository
     */
    private $inductionRepository;
    /**
     * @var PaymentRepository
     */
    private $paymentRepository;
    /**
     * @var SubscriptionChargeRepository
     */
    private $subscriptionChargeRepository;

    /**
     * @param UserRepository               $userRepository
     * @param InductionRepository          $inductionRepository
     * @param PaymentRepository            $paymentRepository
     * @param SubscriptionChargeRepository $subscriptionChargeRepository
     */
    public function __construct(UserRepository $userRepository, InductionRepository $inductionRepository, PaymentRepository $paymentRepository, SubscriptionChargeRepository $subscriptionChargeRepository)
    {
        $this->userRepository = $userRepository;
        $this->inductionRepository = $inductionRepository;
        $this->paymentRepository = $paymentRepository;
        $this->subscriptionChargeRepository = $subscriptionChargeRepository;
    }


    /**
     * Subscribe to the payment events
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('payment.create', 'BB\Handlers\PaymentEventHandler@onCreate');
        $events->listen('payment.delete', 'BB\Handlers\PaymentEventHandler@onDelete');
        $events->listen('payment.cancelled', 'BB\Handlers\PaymentEventHandler@onCancel');
        $events->listen('payment.paid', 'BB\Handlers\PaymentEventHandler@onPaid');
    }


    /**
     * New payment record is created
     *
     * @param $userId
     * @param $reason
     * @param $ref
     * @param $paymentId
     * @param $status
     */
    public function onCreate($userId, $reason, $ref, $paymentId, $status)
    {
        if ($reason == 'balance') {

            $this->updateBalance($userId);

        } elseif ($reason == 'subscription') {

            $this->updateSubPayment($paymentId, $userId, $status);

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
     * A payment has been deleted
     *
     * @param $userId
     * @param $source
     * @param $reason
     * @param $paymentId
     */
    public function onDelete($userId, $source, $reason, $paymentId)
    {
        if (($source == 'balance') || ($reason == 'balance')) {
            $this->updateBalance($userId);
        }
        if ($reason == 'induction') {

        }

        if ($reason == 'storage-box') {

        }

        if ($reason == 'subscription') {

        }
    }

    /**
     * A payment has been cancelled
     *
     * @param $paymentId
     * @param $userId
     * @param $reason
     * @param $ref
     * @param $status
     */
    public function onCancel($paymentId, $userId, $reason, $ref, $status)
    {
        if ($reason == 'subscription') {
            $this->subscriptionChargeRepository->paymentFailed($ref);
        }
    }

    /**
     * A payment has been marked as paid
     *
     * @param $userId
     * @param $paymentId
     * @param $reason
     * @param $reference
     * @param $paymentDate
     */
    public function onPaid($userId, $paymentId, $reason, $reference, $paymentDate)
    {
        if (($reason == 'subscription') && $reference) {
            //For subscription payments the reference is the charge id
            $this->subscriptionChargeRepository->markChargeAsPaid($reference, $paymentDate);
        }
    }


    private function updateBalance($userId)
    {
        $memberCreditService = \App::make('\BB\Services\Credit');
        $memberCreditService->setUserId($userId);
        $memberCreditService->recalculate();
    }

    private function updateSubPayment($paymentId, $userId, $status)
    {
        $payment = $this->paymentRepository->getById($paymentId);
        $subCharge = $this->subscriptionChargeRepository->findCharge($userId);
        if (!$subCharge) {
            \Log::warning("Subscription payment without a sub charge. Payment ID:".$paymentId);
            return;
        }

        //The sub charge record id gets saved onto the payment
        if (empty($payment->reference)) {
            $payment->reference = $subCharge->id;
            $payment->save();
        } else if ($payment->reference != $subCharge->id) {
            throw new PaymentException("Attempting to update sub charge ({$subCharge->id}) but payment ({$payment->id}) doesn't match");
        }

        if ($status == 'paid') {
            $this->subscriptionChargeRepository->markChargeAsPaid($subCharge->id);
        } else if ($status == 'pending') {
            $this->subscriptionChargeRepository->markChargeAsProcessing($subCharge->id);
        }

        //The amount isn't stored on the sub charge record until its paid or processing
        if ($payment->amount != $subCharge->amount) {
            $this->subscriptionChargeRepository->updateAmount($subCharge->id, $payment->amount);
        }
    }

    private function createInductionRecord($userId, $ref, $paymentId)
    {
        /* @TODO: Replace with a repo */
        /* @TODO: Verify payment amount is valid - this could have been changed */
        Induction::create([
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