<?php namespace BB\Entities;


use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Payment extends Model {

    use PresentableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'source', 'source_id', 'user_id', 'amount', 'fee', 'amount_minus_fee', 'status', 'reason', 'created_at', 'reference'
    ];


    protected $attributes = [
        'status' => 'pending',
        'fee' => 0,
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'paid_at');
    }


    protected $presenter = 'BB\Presenters\PaymentPresenter';


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function scopeSubscription($query)
    {
        return $query->whereReason('subscription');
    }
}
