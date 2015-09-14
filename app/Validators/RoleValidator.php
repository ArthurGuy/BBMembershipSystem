<?php namespace BB\Validators;

class RoleValidator extends FormValidator
{

    protected $rules = [
        'title'         => 'required',
        'description'   => '',
    ];


} 