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
        'source', 'source_id', 'user_id', 'amount', 'fee', 'amount_minus_fee', 'status', 'reason'
    ];


    protected $attributes = [
        'status' => 'pending'
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }
}
