<?php namespace BB\Validators;

class UserDetails extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'trusted' => 'required_if:key_holder,1',
        'key_holder' => '',
        'induction_completed' => '',
    ];

} 