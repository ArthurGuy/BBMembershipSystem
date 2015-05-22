<?php namespace BB\Validators;

class ExpenseValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'category'    => 'required',
        'description' => 'required',
        'amount'      => 'required',
        'date'        => 'date_format:Y-m-d|before:tomorrow',
        'file'        => 'mimes:jpeg,png,pdf',
    ];

} 