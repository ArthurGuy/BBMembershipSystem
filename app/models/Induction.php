<?php


class Induction extends Eloquent {

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


    public static function inductionList($key=null)
    {
        $items = [
            'laser' => (object)[
                    'name' => 'Laser Cutter',
                    'cost' => '50',
                ],
            'lathe' => (object)[
                    'name' => 'Lathe',
                    'cost' => '25',
                ],
            'welder' => (object)[
                    'name' => 'Welder',
                    'cost' => '25',
                ],
            'cnc' => (object)[
                    'name' => 'CNC Router',
                    'cost' => '25'
                ],
            '3dprinter' => (object)[
                    'name' => '3D Printer',
                    'cost' => '25'
                ]
        ];
        if ($key)
        {
            return $items[$key];
        }
        return $items;
    }


    public function user()
    {
        return $this->belongsTo('User');
    }

    public function payment()
    {
        return $this->belongsTo('Payment');
    }

    public static function findExisting($userId, $key)
    {
        return self::where('user_id', $userId)->where('key', $key)->first();
    }

    public static function userInductions($userId)
    {

    }
}
