<?php namespace BB\Repo;

use BB\Entities\SubscriptionCharge;

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

}