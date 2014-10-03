<?php namespace BB\Repo;

class PaymentRepository extends DBRepository
{

    /**
     * @var Payment
     */
    protected $model;

    static $SUBSCRIPTION = 'subscription';
    static $INDUCTION = 'induction';

    function __construct(\Payment $model)
    {
        $this->model = $model;
    }

    public function recordPayment($reason, $userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0)
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

    public function recordSubscriptionPayment($userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0)
    {
        return $this->recordSubscriptionPayment('subscription', $userId, $source, $sourceId, $amount, $status, $fee);
    }

    public function updateStatus($paymentId, $status)
    {
        $record = $this->model->findOrFail($paymentId);
    }
} 