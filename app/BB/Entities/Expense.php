<?php namespace BB\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Expense
 *
 * @property bool $active
 * @property integer $user_id
 * @package BB\Entities
 */
class Expense extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expenses';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'description',
        'amount'
    ];


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }


} 