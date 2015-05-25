<?php namespace BB\Validators;

class ExpenseValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'user_id'      => 'required',
        'category'     => 'required',
        'description'  => 'required',
        'amount'       => 'required|integer|max:100000',
        'expense_date' => 'required|date_format:d/m/y|before:tomorrow',
        'file'         => 'required|mimes:jpeg,png,pdf',
    ];

} 