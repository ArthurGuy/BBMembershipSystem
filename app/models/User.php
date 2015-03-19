<?php

use BB\Helpers\MembershipPayments;
use BB\Traits\UserRoleTrait;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Laracasts\Presenter\PresentableTrait;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait, UserRoleTrait, PresentableTrait;

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

    protected $auditFields = array('induction_completed', 'trusted', 'key_holder');

    protected $presenter = 'BB\Presenters\UserPresenter';


    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'given_name', 'family_name', 'email', 'secondary_email', 'password', 'emergency_contact', 'phone',
        'monthly_subscription', 'profile_private',
        'key_holder', 'key_deposit_payment_id', 'trusted', 'induction_completed', 'payment_method', 'active', 'status'
    ];


    protected $attributes = [
        'status'                => 'setting-up',
        'active'                => 0,
        'key_holder'            => 0,
        'trusted'               => 0,
        'email_verified'        => 0,
        'founder'               => 0,
        'director'              => 0,
        'induction_completed'   => 0,
        'payment_day'           => 1,
        'profile_private'       => 0,
        'cash_balance'          => 0,
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

        self::observe(new \BB\Observer\UserAuditObserver());
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


    # Relationships

    public function payments()
    {
        return $this->hasMany('Payment')->orderBy('created_at', 'desc');
    }

    public function inductions()
    {
        return $this->hasMany('Induction');
    }

    public function keyFob()
    {
        return $this->hasMany('KeyFob')->where('active', true)->first();
    }

    public function profile()
    {
        return $this->hasOne('ProfileData');
    }

    public function address()
    {
        return $this->hasOne('\BB\Entities\Address')->orderBy('approved', 'asc');
    }


    public function updateSubscription($paymentMethod, $paymentDay)
    {
        $this->attributes['payment_method'] = $paymentMethod;
        $this->attributes['payment_day'] = $paymentDay;

        $this->save();
    }

    public function updateSubAmount($amount)
    {
        $this->attributes['monthly_subscription'] = $amount;
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

    //Used for profile photos
    public function shouldMemberSeeProtectedPhoto()
    {
        switch ($this->attributes['status']) {
            case 'active':
            case 'payment-warning':
            case 'honorary':
                return true;
            default:
                return false;
        }
    }

    /**
     * Is this user considered a keyholder - can they use the space on their own
     * @return bool
     */
    public function keyholderStatus()
    {
        if ($this->active && $this->key_holder && $this->trusted) {
            return true;
        }
        return false;
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

    public function scopeLeaving($query)
    {
        return $query->where('status', '=', 'leaving');
    }

    public function scopePaymentWarning($query)
    {
        return $query->where('status', '=', 'payment-warning');
    }



    # Methods


    public static function activePublicList()
    {
        return self::active()->where('status', '!=', 'leaving')->orderBy('given_name')->get();
    }

    public function cancelSubscription()
    {
        $this->payment_method = '';
        $this->subscription_id = '';
        $this->payment_day = '';
        $this->status = 'payment-warning';
        $this->save();
    }

    public function setLeaving()
    {
        //If their payment has run out mark them as left immediately otherwise set them as leaving
        $cutOffDate = MembershipPayments::getSubGracePeriodDate($this->paument_method);
        if ($this->subscription_expires->lt($cutOffDate))
        {
            $this->active = false;
            $this->status = 'left';
        }
        else
        {
            $this->status = 'leaving';
        }
        $this->save();
    }

    public function leave()
    {
        $this->status = 'left';
        $this->active = false;
        $this->save();
    }

    public function rejoin()
    {
        $this->status = 'setting-up';
        $this->save();
    }

    public function emailConfirmed()
    {
        $this->email_verified = true;
        $this->save();
    }

    /*
    public function profilePhoto($photoAvailable=true)
    {
        $this->profile_photo = $photoAvailable;
        $this->save();
    }
    */

    public function isAdmin()
    {
        return Auth::user()->hasRole('admin');
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
        if (Auth::user()->hasRole('admin'))
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

    public static function dropdown($activeOnly = null)
    {
        $userArray = [];
        if ($activeOnly) {
            $users = self::active()->get();
        } else {
            $users = self::all();
        }
        foreach ($users as $user)
        {
            $userArray[$user->id] = $user->name;
        }
        return $userArray;
    }

    public function getStorageBoxPayment()
    {
        if ($this->storage_box_payment_id) {
            $payment = Payment::find($this->storage_box_payment_id);
            if ($payment) {
                return $payment;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getAuditFields()
    {
        return $this->auditFields;
    }

    public function getAlerts()
    {
        $alerts = [];
        if (!$this->profile->profile_photo && !$this->profile->new_profile_photo) {
            $alerts[] = 'missing-profile-photo';
        }
        if (empty($this->phone)) {
            $alerts[] = 'missing-phone';
        }
        return $alerts;
    }

    /**
     * @return bool
     */
    public function isBanned()
    {
        return $this->banned;
    }
}
