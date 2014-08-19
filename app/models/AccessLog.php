<?php 

class AccessLog extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'access_log';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'key_fob_id', 'response', 'service'
    ];


    public function user()
    {
        return $this->belongsTo('User');
    }

    public function keyFob()
    {
        return $this->belongsTo('KeyFob');
    }

} 