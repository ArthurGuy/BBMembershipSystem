<?php namespace BB\Repo;

use BB\Entities\Payment;
use BB\Exceptions\NotImplementedException;

class PaymentRepository extends DBRepository
{

    /**
     * @var Payment
     */
    protected $model;

    static $SUBSCRIPTION = 'subscription';
    static $INDUCTION = 'induction';


    private $startDate = null;
    private $endDate = null;
    private $memberId = null;
    private $reason = null;
    private $source = null;

    /**
     * @param Payment $model
     */
    function __construct(Payment $model)
    {
        $this->model = $model;
        $this->perPage = 10;
    }


    public function getPaginated(array $params)
    {
        $model = $this->model;

        if ($this->hasDateFilter()) {
            $model = $model->where('created_at', '>=', $this->startDate)->where('created_at', '<=', $this->endDate);
        }

        if ($this->hasMemberFilter()) {
            $model = $model->where('user_id', $this->memberId);
        }

        if ($this->hasReasonFilter()) {
            $model = $model->where('reason', $this->reason);
        }

        if ($this->hasSourceFilter()) {
            $model = $model->where('source', $this->source);
        }

        if ($this->isSortable($params)) {
            return $model->orderBy($params['sortBy'], $params['direction'])->paginate($this->perPage);
        }
        return $model->paginate($this->perPage);
    }


    public function getTotalAmount()
    {
        $model = $this->model;

        if ($this->hasDateFilter()) {
            $model = $model->where('created_at', '>=', $this->startDate)->where('created_at', '<=', $this->endDate);
        }

        if ($this->hasMemberFilter()) {
            $model = $model->where('user_id', $this->memberId);
        }

        if ($this->hasReasonFilter()) {
            $model = $model->where('reason', $this->reason);
        }

        if ($this->hasSourceFilter()) {
            $model = $model->where('source', $this->source);
        }

        return $model->get()->sum('amount');
    }


    /**
     * Record a payment against a user record
     * @param string $reason What was the reason. subscription, induction, etc...
     * @param int    $userId The users ID
     * @param string $source gocardless, paypal
     * @param string $sourceId A reference for the source
     * @param double $amount Amount received before a fee
     * @param string $status paid, pending, cancelled, refunded
     * @param double $fee The fee charged by the payment provider
     * @param string $ref
     * @return int  The ID of the payment record
     */
    public function recordPayment($reason, $userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0.0, $ref=null)
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
        $record->reference        = $ref;
        $record->save();

        //Emit an event so that things like the balance updater can run
        \Event::fire('payment.create', array($userId, $reason, $ref, $record->id, $status));

        return $record->id;
    }

    /**
     * Record a subscription payment
     * @param int    $userId The users ID
     * @param string $source gocardless, paypal
     * @param string $sourceId A reference for the source
     * @param double $amount Amount received before a fee
     * @param string $status paid, pending, cancelled, refunded
     * @param double $fee The fee charged by the payment provider
     * @param null   $ref
     * @return int  The ID of the payment record
     */
    public function recordSubscriptionPayment($userId, $source, $sourceId, $amount, $status = 'paid', $fee = 0.0, $ref=null)
    {
        return $this->recordPayment('subscription', $userId, $source, $sourceId, $amount, $status, $fee, $ref);
    }

    /**
     * Record a payment failure or cancellation
     *
     * @param int    $paymentId
     * @param string $status
     */
    public function recordPaymentFailure($paymentId, $status='failed')
    {
        $this->update($paymentId, ['status' => $status]);

        $payment = $this->getById($paymentId);

        \Event::fire('payment.cancelled', array($paymentId, $payment->user_id, $payment->reason, $payment->ref, $status));
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
     * @param string $reason
     * @return mixed
     */
    public function getUserPaymentsByReason($userId, $reason)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('reason = ? and (status = ? or status = ? or status = ?)', [$reason, 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    /**
     * @param string $source
     */
    public function getUserPaymentsBySource($userId, $source)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('source = ? and (status = ? or status = ? or status = ?)', [$source, 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all payments with a specific reference
     * @param string $reference
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPaymentsByReference($reference)
    {
        return $this->model->where('reference', $reference)->get();
    }


    /**
     * Return a paginated list of balance affecting payment for a user
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBalancePaymentsPaginated($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('(source = ? or reason = ?) and (status = ? or status = ? or status = ?)', ['balance', 'balance', 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->simplePaginate($this->perPage);
    }


    /**
     * Return a collection of payments specifically for storage boxes
     * @param integer $userId
     * @return mixed
     */
    public function getStorageBoxPayments($userId)
    {
        return $this->model->where('user_id', $userId)
            ->whereRaw('reason = ? and (status = ? or status = ? or status = ?)', ['storage-box', 'paid', 'pending', 'withdrawn'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function dateFilter($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    private function hasDateFilter()
    {
        return ($this->startDate && $this->endDate);
    }

    /**
     * Delete a record
     * @param $recordId
     * @return bool|null
     * @throws \Exception
     */
    public function delete($recordId)
    {
        $payment = $this->getById($recordId);

        $state = $payment->delete();

        //Fire an event, allows the balance to get updated
        \Event::fire('payment.delete', array($payment->user_id, $payment->source, $payment->reason, $payment->id));

        return $state;
    }

    public function canDelete($recordId)
    {
        throw new NotImplementedException();
    }

    /**
     * Used for the getPaginated and getTotalAmount method
     * @param $memberFilter
     */
    public function memberFilter($memberFilter)
    {
        $this->memberId = $memberFilter;
    }

    private function hasMemberFilter()
    {
        return !is_null($this->memberId);
    }

    /**
     * Used for the getPaginated and getTotalAmount method
     * @param $reasonFilter
     */
    public function reasonFilter($reasonFilter)
    {
        $this->reason = $reasonFilter;
    }

    private function hasReasonFilter()
    {
        return !is_null($this->reason);
    }

    /**
     * Used for the getPaginated and getTotalAmount method
     * @param string $sourceFilter
     */
    public function sourceFilter($sourceFilter)
    {
        $this->source = $sourceFilter;
    }


    private function hasSourceFilter()
    {
        return !is_null($this->source);
    }

    /**
     * Used for the getPaginated and getTotalAmount method
     */
    public function resetFilters()
    {
        $this->source = null;
        $this->reason = null;
        $this->memberId = null;
        $this->startDate = null;
        $this->endDate = null;
    }

    /**
     * Fetch a payment record using the id provided by the payment provider
     *
     * @param $sourceId
     * @return Payment
     */
    public function getPaymentBySourceId($sourceId)
    {
        return $this->model->where('source_id', $sourceId)->first();
    }
} 