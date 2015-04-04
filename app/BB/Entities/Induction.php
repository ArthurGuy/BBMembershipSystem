<?php namespace BB\Entities;


use Illuminate\Database\Eloquent\Model;

class Induction extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'inductions';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'key', 'user_id', 'paid', 'payment_id', 'trained', 'active'
    ];


    protected $attributes = [

    ];

    public function getDates()
    {
        return array('created_at', 'updated_at', 'trained');
    }


    public function getIsTrainedAttribute()
    {
        return (!empty($this->trained));
    }


    public function user()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function trainerUser()
    {
        return $this->belongsTo('\BB\Entities\User');
    }

    public function payment()
    {
        return $this->belongsTo('\BB\Entities\Payment');
    }

    public static function findExisting($userId, $key)
    {
        return self::where('user_id', $userId)->where('key', $key)->first();
    }

    public static function userInductions($userId)
    {

    }

    public static function trainersFor($key)
    {
        return self::where('key', $key)->where('is_trainer', 1)->get();
    }

    public static function trainersForDropdown($key)
    {
        $trainers = self::trainersFor($key);
        $trainersArray = [null=>'Unknown'];

        foreach ($trainers as $trainer)
        {
            $trainersArray[$trainer->user->id] = $trainer->user->name;
        }

        return $trainersArray;
    }
}
