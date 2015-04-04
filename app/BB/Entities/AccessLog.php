<?php namespace BB\Entities;

use BB\Observer\AccessLogObserver;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model {

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
        'user_id', 'key_fob_id', 'response', 'service', 'delayed'
    ];


    public static function boot()
    {
        parent::boot();

        self::observe(new AccessLogObserver());
    }


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function keyFob()
    {
        return $this->belongsTo('\BB\Entities\KeyFob');
    }

} 