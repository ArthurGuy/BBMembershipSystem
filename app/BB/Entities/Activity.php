<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 *
 * @property integer $key_fob_id
 * @property integer $user_id
 * @property string $service
 * @property string $response
 * @property bool $delayed
 * @package BB\Entities
 */
class Activity extends Model
{

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
        'user_id', 'key_fob_id', 'response', 'service', 'delayed', 'created_at'
    ];


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function keyFob()
    {
        return $this->belongsTo('\BB\Entities\KeyFob');
    }

} 