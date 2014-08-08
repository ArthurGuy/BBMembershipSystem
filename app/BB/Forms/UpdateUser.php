<?php namespace BB\Forms;

class UpdateUser extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'given_name' => 'required',
        'family_name' => 'required',
        'email' => 'required|email|unique:users,email,{id}',
        'password' => 'min:8',
        'address_line_1' => 'required',
        'address_line_2' => '',
        'address_line_3' => '',
        'address_line_4' => '',
        'address_postcode' => 'required',
        'monthly_subscription' => 'required|numeric|min:5',
        'emergency_contact' => 'required'
    ];

} 