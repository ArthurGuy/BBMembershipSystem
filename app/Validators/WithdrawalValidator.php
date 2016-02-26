<?php namespace BB\Validators;

class WithdrawalValidator extends FormValidator
{

    protected $rules = [
        'amount'         => 'required',
        'sort_code'      => 'required',
        'account_number' => 'required',
    ];


} 