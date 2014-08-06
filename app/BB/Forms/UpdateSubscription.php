<?php namespace BB\Forms;

class UpdateSubscription extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'payment_method' => 'required',
        'payment_day' => 'numeric'
    ];

} 