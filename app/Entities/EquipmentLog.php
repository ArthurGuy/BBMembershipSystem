<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

/**
 * Class EquipmentLog
 *
 * @property integer $id
 * @property Carbon  $started
 * @property Carbon  $last_update
 * @property Carbon  $finished
 * @property Carbon  $updated_at
 * @property Carbon  $created_at
 * @package BB\Entities
 */
class EquipmentLog extends Model
{

    use PresentableTrait;


    protected $presenter = 'BB\Presenters\EquipmentLogPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'equipment_log';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'key_fob_id', 'device', 'active', 'last_update', 'finished', 'notes', 'reason'
    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'started', 'last_update', 'finished');
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