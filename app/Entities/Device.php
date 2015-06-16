<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 *
 * @property string $name
 * @property string $device_id
 * @property string $queued_command
 * @property bool   $monitor_heartbeat
 * @property string $key
 * @property Carbon $last_boot
 * @property Carbon $last_heartbeat
 * @property Carbon $created_at
 * @property Carbon $updated_at
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

    public function heartbeatWarning()
    {
        //If the last heartbeat was more than an hour ago there is an issue
        if ( $this->monitor_heartbeat && ($this->last_heartbeat < Carbon::now()->subHour())) {
            return true;
        }

        return false;
    }

} 