<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class SubscriptionCharge
 *
 * @property integer $id
 * @property integer $user_id
 * @property Carbon  $charge_date
 * @property Carbon  $payment_date
 * @property integer $amount
 * @property string  $status
 * @package BB\Entities
 */
class SubscriptionCharge extends Model
{

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

    protected $attributes = [
        'payment_date' => '0000-00-00 00:00:00'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'charge_date', 'payment_date');
    }


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

} 