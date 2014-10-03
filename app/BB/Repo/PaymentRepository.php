<?php namespace BB\Repo;

class PaymentRepository extends DBRepository
{

    /**
     * @var \Payment
     */
    protected $model;

    static $SUBSCRIPTION = 'subscription';
    static $INDUCTION = 'induction';

    /**
     * @param \Payment $model
     */
    function __construct(\Payment $model)
    {
        $this->model = $model;
    }


    /**
     * Record a payment against a user record
     * @param string $reason    What was the reason. subscription, induction, etc...
     * @param int    $userId    The users ID
     * @param string $source    gocardless, paypal
     * @param string $sourceId  A reference for the source
     * @param double $amount    Amount received before a fee
     * @param string $status    paid, pending, cancelled, refunded
     * @param double $fee       The fee charged by the payment provider
     * @return int  The ID of the payment record
     */
    public function recordPayment($reason, $userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0.0)
    {
        $record                   = new $this->model;
        $record->user_id          = $userId;
        $record->reason           = $reason;
        $record->source           = $source;
        $record->source_id        = $sourceId;
        $record->amount           = $amount;
        $record->amount_minus_fee = ($amount - $fee);
        $record->fee              = $fee;
        $record->status           = $status;
        $record->save();
        return $record->id;
    }

    /**
     * Record a subscription payment
     * @param int    $userId    The users ID
     * @param string $source    gocardless, paypal
     * @param string $sourceId  A reference for the source
     * @param double $amount    Amount received before a fee
     * @param string $status    paid, pending, cancelled, refunded
     * @param double $fee       The fee charged by the payment provider
     * @return int  The ID of the payment record
     */
    public function recordSubscriptionPayment($userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0.0)
    {
        return $this->recordSubscriptionPayment('subscription', $userId, $source, $sourceId, $amount, $status, $fee);
    }

    public function updateStatus($paymentId, $status)
    {
        $record = $this->model->findOrFail($paymentId);
    }
} 