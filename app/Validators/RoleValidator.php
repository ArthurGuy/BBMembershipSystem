<?php namespace BB\Validators;

class RoleValidator extends FormValidator
{

    protected $rules = [
        'title'         => 'required',
        'description'   => '',
        'public_email'  => 'email',
        'private_email' => 'email',
        'slack_channel' => '',
    ];


} 