<?php namespace BB\Validators;

class ACSValidator extends FormValidator
{

    protected $rules = [
        'device'  => 'required|max:50',
        'key_fob' => 'max:50',
        'message' => 'max:50',
    ];

} 