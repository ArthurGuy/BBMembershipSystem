<?php namespace BB\Entities;

use BB\Exceptions\AuthenticationException;
use BB\Helpers\MembershipPayments;
use BB\Traits\UserRoleTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use Auth;
use Hash;

class User extends Model implements UserInterface, RemindableInterface {

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
        'payment_day'           => 0,
        'profile_private'       => 0,
        'cash_balance'          => 0,
    ];


    public function getDates()
    {
        return array('created_at', 'updated_at', 'subscription_expires', 'banned_date');
    }


    public static function statuses()
    {
        return [
            'setting-up'        => 'Setting Up',
            'active'            => 'Active',
            'payment-warning'   => 'Payment Warning',
            'suspended'         => 'Suspended',
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



    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | The connections between this model and others
    |
    */

    public function payments()
    {
        return $this->hasMany('\BB\Entities\Payment')->orderBy('created_at', 'desc');
    }

    public function inductions()
    {
        return $this->hasMany('\BB\Entities\Induction');
    }

    public function keyFob()
    {
        return $this->hasMany('\BB\Entities\KeyFob')->where('active', true)->first();
    }

    public function profile()
    {
        return $this->hasOne('\BB\Entities\ProfileData');
    }

    public function address()
    {
        return $this->hasOne('\BB\Entities\Address')->orderBy('approved', 'asc');
    }



    /*
    |--------------------------------------------------------------------------
    | Attribute Getters and Setters and Model Extensions
    |--------------------------------------------------------------------------
    |
    | Useful properties and methods to have on a user model
    |
    */

    public function getNameAttribute()
    {
        return $this->attributes['given_name'] . ' ' . $this->attributes['family_name'];
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function setPaymentDayAttribute($value)
    {
        //Ensure the payment date will always exist on any monthpayment_date
        if ($value > 28) {
            $value = 1;
        }
        $this->attributes['payment_day'] = $value;
    }

    /**
     * Can the user see protected member photos?
     * Only available to active members
     *
     * @return bool
     */
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
     * Is the user on a payment method that allows their subscription amount to be changed
     *
     * @return bool
     */
    public function canMemberChangeSubAmount()
    {
        return ($this->attributes['payment_method'] == 'gocardless-variable');
    }

    /**
     * Is this user considered a keyholder - can they use the space on their own
     * This may have been replaced by a repo method which checks the photo as well
     *
     * @return bool
     */
    public function keyholderStatus()
    {
        if ($this->active && $this->key_holder && $this->trusted) {
            return true;
        }
        return false;
    }

    /**
     * Is the user part of the admin group
     *
     * @return bool
     */
    public function isAdmin()
    {
        return Auth::user()->hasRole('admin');
    }

    /**
     * Should GoCardless be promoted to the user
     *
     * @return bool
     */
    public function promoteGoCardless()
    {
        if (($this->payment_method != 'gocardless' && $this->payment_method != 'gocardless-variable') && ($this->status == 'active')) {
            return true;
        }
        return false;
    }


    public function promoteVariableGoCardless()
    {
        return (($this->status == 'active') && ($this->payment_method == 'gocardless'));
    }

    public function promoteGetAKey()
    {
        return ($this->trusted && !$this->key_holder && ($this->status == 'active'));
    }

    /**
     * Get an array of alerts for the user
     *
     * @return array
     */
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

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return ($this->status == 'suspended');
    }



    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

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

    public function scopeSuspended($query)
    {
        return $query->where('status', '=', 'suspended');
    }


    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public static function activePublicList()
    {
        return self::active()->where('status', '!=', 'leaving')->orderBy('given_name')->get();
    }

    /**
     * @param $paymentMethod
     * @param $paymentDay
     * @depreciated
     */
    public function updateSubscription($paymentMethod, $paymentDay)
    {
        //We might need to do something about the payment day to ensure its before the 28th
        $this->attributes['payment_method'] = $paymentMethod;
        $this->attributes['payment_day'] = $paymentDay;

        $this->save();
    }

    public function updateSubAmount($amount)
    {
        $this->attributes['monthly_subscription'] = $amount;
        $this->save();
    }

    public function cancelSubscription()
    {
        $this->payment_method = '';
        $this->subscription_id = '';
        $this->payment_day = '';
        $this->status = 'leaving';
        $this->save();
    }

    public function setLeaving()
    {
        $this->status = 'leaving';
        $this->save();
    }

    public function setSuspended()
    {
        $this->status = 'suspended';
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

    /**
     * Fetch a user record, performs a permission check
     * @param null $id
     * @return User
     * @throws AuthenticationException
     */
    public static function findWithPermission($id = null)
    {
        if (!$id) {
            //Return the logged in user
            return Auth::user();
        }

        $requestedUser = self::findOrFail($id);
        if (Auth::user()->id == $requestedUser->id) {
            //The user they are after is themselves
            return $requestedUser;
        }

        //They are requesting a user that isn't them
        if (Auth::user()->hasRole('admin')) {
            //They are an admin so that's alright
            return $requestedUser;
        }

        throw new AuthenticationException();
    }


    public function extendMembership($paymentMethod = null, DateTime $expiry = null)
    {
        if (empty($expiry)) {
            $expiry = Carbon::now()->addMonth();
        }
        $this->status = 'active';
        $this->active = true;
        if ($paymentMethod) {
            $this->payment_method = $paymentMethod;
        }
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
        foreach ($users as $user) {
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


}
