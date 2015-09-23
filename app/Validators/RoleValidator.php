<?php namespace BB\Validators;

class RoleValidator extends FormValidator
{

    protected $rules = [
        'title'         => 'required',
        'description'   => '',
        'email_public'  => 'email',
        'email_private' => 'email',
        'slack_channel' => '',
    ];


} 