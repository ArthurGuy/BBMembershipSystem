<?php namespace BB\Validators;

class ACSValidator extends FormValidator
{

    protected $rules = [
        'device'  => 'required|max:25',
        'key_fob' => 'max:15',
        'message' => 'required|max:25',
        'type'    => 'required|in:door,equipment',
        'time'    => 'max:10',
    ];

} 