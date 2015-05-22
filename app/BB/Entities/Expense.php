<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Expense
 *
 * @property integer $id
 * @property integer $user_id
 * @property User    $user
 * @property string  $category
 * @property string  $description
 * @property integer $amount
 * @property Carbon  $expense_date
 * @property string  $file
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
        'amount',
        'expense_date',
        'user_id',
        'file',
    ];


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }


} 