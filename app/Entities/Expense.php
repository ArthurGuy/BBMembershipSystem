<?php namespace BB\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

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
 * @property bool    $approved
 * @property bool    $declined
 * @property integer $approved_by_user
 * @package BB\Entities
 */
class Expense extends Model
{
    use PresentableTrait;

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

    protected $casts = [
        'approved' => 'boolean',
    ];

    protected $presenter = 'BB\Presenters\ExpensePresenter';

    public function getDates()
    {
        return array('created_at', 'updated_at', 'expense_date');
    }

    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function approvedBy()
    {
        return $this->belongsTo('\BB\Entities\User', 'approved_by_user');
    }


} 