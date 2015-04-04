<?php namespace BB\Entities;

use Laracasts\Presenter\PresentableTrait;

class SubscriptionCharge extends \Eloquent {

    use PresentableTrait;

    protected $presenter = 'BB\Presenters\SubscriptionChargePresenter';

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
        return array('created_at', 'updated_at', 'charge_date', 'payment_date');
    }


    public function user()
    {
        return $this->hasOne('\BB\Entities\User');
    }

} 