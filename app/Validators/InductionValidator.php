<?php namespace BB\Validators;

class InductionValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'induction_completed'   => 'accepted',
        'rules_agreed'          => 'accepted',
    ];


} 