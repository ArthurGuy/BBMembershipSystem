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
        $this->perPage = 5;
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
        return $this->recordPayment('subscription', $userId, $source, $sourceId, $amount, $status, $fee);
    }

    public function updateStatus($paymentId, $status)
    {
        $record = $this->model->findOrFail($paymentId);
    }


    /**
     * Fetch the users latest payment of a particular type
     * @param integer $userId
     * @param string  $reason
     * @return mixed
     */
    public function latestUserPayment($userId, $reason='subscription')
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('reason = ? and (status = ? or status = ? or status = ?)', [$reason, 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->first();
    }


    /**
     * Get all user payments of a specific reason
     * @param $userId
     * @param $reason
     * @return mixed
     */
    public function getUserPaymentsByReason($userId, $reason)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('reason = ? and (status = ? or status = ? or status = ?)', [$reason, 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function getUserPaymentsBySource($userId, $source)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('source = ? and (status = ? or status = ? or status = ?)', [$source, 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    /**
     * Return a paginated list of balance affecting payment for a user
     * @param $userId
     * @return mixed
     */
    public function getBalancePaymentsPaginated($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('(source = ? or reason = ?) and (status = ? or status = ? or status = ?)', ['balance', 'balance', 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->simplePaginate($this->perPage);
    }

} 