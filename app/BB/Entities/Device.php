<?php namespace BB\Entities;

class Device extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'devices';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'last_heartbeat', 'last_boot'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'last_boot', 'last_heartbeat');
    }

} 