<?php namespace BB\Entities;

class Address extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_address';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'line_1', 'line_2', 'line_3', 'line_4', 'postcode', 'hash'
    ];


    public function user()
    {
        return $this->hasOne('\BB\Entities\User');
    }

} 