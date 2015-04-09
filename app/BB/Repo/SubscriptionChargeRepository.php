<?php namespace BB\Repo;

use BB\Entities\SubscriptionCharge;
use BB\Exceptions\InvalidDataException;
use Carbon\Carbon;

class SubscriptionChargeRepository extends DBRepository
{


    /**
     * @var SubscriptionCharge
     */
    protected $model;

    function __construct(SubscriptionCharge $model)
    {
        $this->model = $model;
    }

    /**
     * @param integer   $userId
     * @param \DateTime $date
     * @param integer   $amount
     * @return SubscriptionCharge
     */
    public function createCharge($userId, $date, $amount)
    {
        return $this->model->create(['charge_date' => $date, 'user_id' => $userId, 'amount' => $amount, 'status'=>'pending']);
    }

    /**
     * Does a charge already exist for the user and date
     * @param $userId
     * @param $date
     * @return bool
     */
    public function chargeExists($userId, $date)
    {
        if ($this->model->where('user_id', $userId)->where('charge_date', $date)->count() !== 0) {
            return true;
        }
        return false;
    }

    /**
     * Locate the next payment the user has to pay off
     * @param $userId
     * @param Carbon $paymentDate
     * @return mixed
     */
    public function findCharge($userId, $paymentDate=null)
    {
        //find any existing payment that hasn't been paid
        //Subscription payments will always be used to pay of bills


        return $this->model->where('user_id', $userId)->whereIn('status', ['pending', 'due'])->orderBy('charge_date', 'ASC')->first();
    }

    /**
     * @param $chargeId
     * @param $paymentDate
     */
    public function markChargeAsPaid($chargeId, $paymentDate=null)
    {
        if (is_null($paymentDate)) {
            $paymentDate = new Carbon();
        }
        $subCharge = $this->getById($chargeId);
        $subCharge->payment_date = $paymentDate;
        $subCharge->status = 'paid';
        $subCharge->save();
    }

    /**
     * @param      $chargeId
     * @param      $status
     * @param null $paymentDate
     * @throws InvalidDataException
     */
    public function updateChargeStatus($chargeId, $status, $paymentDate=null)
    {
        if (!in_array($status, ['paid', 'pending'])) {
            throw new InvalidDataException("Status not supported");
        }
        if (is_null($paymentDate)) {
            $paymentDate = new Carbon();
        }
        $subCharge = $this->getById($chargeId);
        $subCharge->status = $status;
        if ($status == 'paid') {
            $subCharge->payment_date = $paymentDate;
        }
        $subCharge->save();
    }

    /**
     * If a payment has failed update the sub charge to reflect this
     * @param $chargeId
     */
    public function paymentFailed($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->payment_date = null;
        $subCharge->status = 'due';
        $subCharge->save();
    }

    /**
     * Return a paginated list of member payments
     * @param integer $userId
     * @return mixed
     */
    public function getMemberChargesPaginated($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('charge_date', 'DESC')->paginate();
    }

    /**
     * Return a paginated list of member payments
     * @return mixed
     */
    public function getChargesPaginated()
    {
        return $this->model->orderBy('charge_date', 'DESC')->paginate();
    }

    /**
     * Get all the charges which are due payment
     *
     * @return mixed
     */
    public function getDue()
    {
        return $this->model->where('status', 'due')->get();
    }

    /**
     * Get charges that are newly created and pending
     *
     * @return mixed
     */
    public function getPending()
    {
        return $this->model->where('status', 'pending')->get();
    }

    /**
     * Update a charge and mark it as due
     * @param $chargeId
     */
    public function setDue($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->status = 'due';
        $subCharge->save();
    }

}