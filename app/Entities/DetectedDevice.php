<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetectedDevice
 *
 * @property integer $id
 * @property string  $type
 * @property string  $mac_address
 * @property string  $display_name
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @package BB\Entities
 */
class DetectedDevice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'detected_devices';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'type', 'mac_address', 'display_name'
    ];

} 