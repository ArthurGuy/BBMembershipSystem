<?php namespace BB\Forms;

class Register extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'given_name' => 'required',
        'family_name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'address_line_1' => 'required',
        'address_line_2' => '',
        'address_line_3' => '',
        'address_line_4' => '',
        'address_postcode' => 'required',
        'monthly_subscription' => 'required|numeric',
        'emergency_contact' => 'required'
    ];

} 