<?php namespace BB\Validators;

class RoleUserValidator extends FormValidator
{

    protected $rules = [
        'user_id' => 'required|exists:users,id',
    ];


} 