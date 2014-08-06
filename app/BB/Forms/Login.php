<?php namespace BB\Forms;

class Login extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:8'
    ];

} 