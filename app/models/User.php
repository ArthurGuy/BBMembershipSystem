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
        'monthly_subscription', 'last_subscription_payment'
    ];


    protected $attributes = [
        'type' => 'member',
        'status' => 'pending',
        'active' => 0,
        'key_holder' => 0,
        'trusted' => ''
    ];


    public function getDates()
    {
        return array('created_at', 'updated_at', 'last_subscription_payment');
    }



    public function payments()
    {
        return $this->hasMany('Payment');
    }

    public function inductions()
    {
        return $this->hasMany('Induction');
    }



    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function cancelSubscription()
    {
        $this->payment_method = '';
        $this->subscription_id = '';
        $this->payment_day = '';
        $this->status = 'pending';
        $this->active = false;
        $this->save();
    }

    public function isAdmin()
    {
        return ($this->type == 'admin')
            ? true
            : false;
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
}
