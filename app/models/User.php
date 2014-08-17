<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'given_name', 'family_name', 'email', 'secondary_email', 'password', 'address_line_1',
        'address_line_2', 'address_line_3', 'address_line_4', 'address_postcode', 'emergency_contact',
        'monthly_subscription', 'profile_photo_private',
        'notes', 'key_holder', 'trusted', 'payment_method', 'payment_day', 'active', 'status', 'created_at', 'founder', 'banned_date', 'induction_completed'
    ];


    protected $attributes = [
        'type' => 'member',
        'status' => 'setting-up',
        'active' => 0,
        'key_holder' => 0,
        'trusted' => '',
    ];


    public function getDates()
    {
        return array('created_at', 'updated_at', 'subscription_expires', 'banned_date');
    }


    public static function boot()
    {
        parent::boot();

        //The welcome email gets fired from this observer
        self::observe(new \BB\Observer\UserObserver());
    }


    public static function statuses()
    {
        return [
            'setting-up'        => 'Setting Up',
            'active'            => 'Active',
            'payment-warning'   => 'Payment Warning',
            'leaving'           => 'Leaving',
            'on-hold'           => 'On Hold',
            'left'              => 'Left',
            'honorary'          => 'Honorary'
        ];
    }

    public static function create(array $input)
    {
        $user = parent::create($input);

        // Find a better way to doing this
        $user->hash = str_random(30);
        $user->save();
        
        return $user;
    }

    public static function statusLabel($status)
    {
        if ($status == 'setting-up')
        {
            return '<span class="label label-warning">Setting Up</span>';
        }
        elseif ($status == 'active')
        {
            return '<span class="label label-success">Active</span>';
        }
        elseif ($status == 'payment-warning')
        {
            return '<span class="label label-danger">Payment Warning</span>';
        }
        elseif ($status == 'leaving')
        {
            return '<span class="label label-default">Leaving</span>';
        }
        elseif ($status == 'on-hold')
        {
            return '<span class="label label-default">On Hold</span>';
        }
        elseif ($status == 'left')
        {
            return '<span class="label label-default">Left</span>';
        }
        elseif ($status == 'honorary')
        {
            return '<span class="label label-default">Honorary</span>';
        }
    }


    public function payments()
    {
        return $this->hasMany('Payment')->orderBy('created_at', 'desc');
    }

    public function inductions()
    {
        return $this->hasMany('Induction');
    }


    public function updateSubscription($paymentMethod, $paymentDay)
    {
        $this->attributes['payment_method'] = $paymentMethod;
        $this->attributes['payment_day'] = $paymentDay;

        $this->save();
    }


    # Getters and Setters

    public function getNameAttribute()
    {
        return $this->attributes['given_name'] . ' ' . $this->attributes['family_name'];
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }



    # Scopes

    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

    public function scopeNotSpecialCase($query)
    {
        return $query->where('status', '!=', 'honorary');
    }



    # Methods


    public static function activePublicList()
    {
        return self::active()->orderBy('given_name')->get();
    }

    public function cancelSubscription()
    {
        $this->payment_method = '';
        $this->subscription_id = '';
        $this->payment_day = '';
        $this->status = 'payment-warning';
        $this->active = false;
        $this->save();
    }

    public function leave()
    {
        $this->status = 'left';
        $this->active = false;
        $this->save();
    }

    public function emailConfirmed()
    {
        $this->email_verified = true;
        $this->save();
    }

    public function profilePhoto($photoAvailable=true)
    {
        $this->profile_photo = $photoAvailable;
        $this->save();
    }

    public function isAdmin()
    {
        return ($this->type == 'admin')
            ? true
            : false;
    }

    public function promoteGoCardless()
    {
        if ($this->payment_method != 'gocardless' && ($this->status == 'active'))
        {
            return true;
        }
        return false;
    }

    /**
     * Fetch a user record, performs a permission check
     * @param null $id
     * @return mixed
     * @throws BB\Exceptions\AuthenticationException
     */
    public static function findWithPermission($id = null)
    {
        if (!$id)
        {
            //Return the logged in user
            return Auth::user();
        }

        $requestedUser = self::findOrFail($id);
        if (Auth::user()->id == $requestedUser->id)
        {
            //The user they are after is themselves
            return $requestedUser;
        }

        //They are requesting a user that isn't them
        if (Auth::user()->isAdmin())
        {
            //They are an admin so that's alright
            return $requestedUser;
        }

        throw new \BB\Exceptions\AuthenticationException();
    }


    public function extendMembership($paymentMethod, DateTime $expiry = null)
    {
        if (empty($expiry))
        {
            $expiry = \Carbon\Carbon::now()->addMonth();
        }
        $this->status = 'active';
        $this->active = true;
        $this->payment_method = $paymentMethod;
        $this->subscription_expires = $expiry;
        $this->save();
    }

    public static function dropdown()
    {
        $userArray = [];
        $users = self::all();
        foreach ($users as $user)
        {
            $userArray[$user->id] = $user->name;
        }
        return $userArray;
    }
}
