<?php namespace BB\Repo;

use BB\Entities\SubscriptionCharge;
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
     * @return mixed
     */
    public function createCharge($userId, $date, $amount)
    {
        return $this->model->create(['charge_date' => $date, 'user_id' => $userId, 'amount' => $amount, 'status'=>'draft']);
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
     * @param $paymentDate
     * @return mixed
     */
    public function findCharge($userId, $paymentDate)
    {
        //find any existing payment that hasn't been paid
        //Subscription payments will always be used to pay of bills


        return $this->model->where('user_id', $userId)->whereIn('status', ['draft', 'pending'])->orderBy('charge_date', 'ASC')->first();
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
     * If a payment has failed update the sub charge to reflect this
     * @param $chargeId
     */
    public function paymentFailed($chargeId)
    {
        $subCharge = $this->getById($chargeId);
        $subCharge->payment_date = null;
        $subCharge->status = 'pending';
        $subCharge->save();
    }

    /**
     * Return a paginated list of member payments
     * @param $userId
     * @return mixed
     */
    public function getMemberChargesPaginated($userId)
    {
        return $this->model->where('user_id', $userId)->paginate();
    }

    public function getDraft()
    {
        return $this->model->where('status', 'draft')->get();
    }

}