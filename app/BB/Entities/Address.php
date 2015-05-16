<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address
 *
 * @property integer $user_id
 * @property string $line_1
 * @property string $line_2
 * @property string $line_3
 * @property string $line_4
 * @property string $postcode
 * @property string $hash
 * @property bool $approved
 * @package BB\Address
 */
class Address extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_address';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'line_1', 'line_2', 'line_3', 'line_4', 'postcode', 'hash'
    ];

    /**
     * @return User
     */
    public function user()
    {
        return $this->hasOne('\BB\Entities\User');
    }

} 