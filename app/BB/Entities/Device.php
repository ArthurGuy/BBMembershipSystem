<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 *
 * @property string $device_key
 * @property bool $requires_induction
 * @property integer $usageCost
 * @property bool $working
 * @property bool $permaloan
 * @property integer $managing_role_id
 * @property $photos
 * @package BB\Entities
 */
class Device extends Model
{

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