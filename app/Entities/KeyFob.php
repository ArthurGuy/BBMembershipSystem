<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class KeyFob
 *
 * @property integer $id
 * @property bool    $lost
 * @property bool    $active
 * @property User    $user
 * @package BB\Entities
 */
class KeyFob extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'key_fobs';

    protected $fillable = [
        'user_id',
        'key_id'
    ];

    protected $attributes = [
        'active' => 1,
        'lost'   => 0,
    ];

    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function markLost()
    {
        $this->lost   = true;
        $this->active = false;
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

    public static function lookup($fobId)
    {
        $record = self::where('key_id', '=', strtoupper($fobId))->active()->first();
        if ( ! $record) {
            throw new ModelNotFoundException;
        }
        return $record;
    }

    public static function lookupPartialTag($fobId)
    {
        $record = self::where('key_id', 'LIKE', '%' . strtoupper($fobId) . '%')->active()->first();
        if ( ! $record) {
            throw new ModelNotFoundException;
        }
        return $record;
    }

} 