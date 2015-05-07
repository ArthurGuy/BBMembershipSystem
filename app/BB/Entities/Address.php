<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

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