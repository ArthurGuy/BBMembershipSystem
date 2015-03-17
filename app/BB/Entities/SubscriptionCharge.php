<?php namespace BB\Entities;

class SubscriptionCharge extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subscription_charge';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'charge_date', 'amount', 'status'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'charge_date');
    }


    public function user()
    {
        return $this->hasOne('\User');
    }

} 