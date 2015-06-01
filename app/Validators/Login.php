<?php namespace BB\Validators;

class Login extends FormValidator
{

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