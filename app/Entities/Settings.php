<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Activity
 *
 * @property integer $id
 * @property integer $key_fob_id
 * @property integer $user_id
 * @property User    $user
 * @property string  $service
 * @property string  $response
 * @property bool    $delayed
 * @property Carbon  $created_at
 * @package BB\Entities
 */
class Settings extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $primaryKey = 'key';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public static function put($key, $value)
    {
        self::create(['key' => $key, 'value' => $value]);
    }

    public static function get($key)
    {
        $setting = self::findOrFail($key);
        return $setting->value;
    }

    public static function change($key, $value)
    {
        $setting = self::findOrFail($key);
        $setting->value = $value;
        $setting->save();
    }


}
