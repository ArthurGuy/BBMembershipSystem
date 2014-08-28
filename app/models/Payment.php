<?php


class Payment extends Eloquent {

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
        'source', 'source_id', 'user_id', 'amount', 'fee', 'amount_minus_fee', 'status', 'reason', 'created_at'
    ];


    protected $attributes = [
        'status' => 'pending',
        'fee' => 0,
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }

    public function scopeSubscription($query)
    {
        return $query->whereReason('subscription');
    }
}
