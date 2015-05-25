<?php namespace BB\Validators;

class ExpenseValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'category'     => 'required',
        'description'  => 'required',
        'amount'       => 'required',
        'expense_date' => 'required|date_format:Y-m-d|before:tomorrow',
        'file'         => 'required|mimes:jpeg,png,pdf',
    ];

} 