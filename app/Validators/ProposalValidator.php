<?php namespace BB\Validators;

class ProposalValidator extends FormValidator
{

    protected $rules = [
        'title'               => 'required',
        'description'         => 'required',
        'start_date'          => 'required|date_format:Y-m-d|after:yesterday',
        'end_date'            => 'required|date_format:Y-m-d|after:today'
    ];

    protected $updateRules = [
        'start_date'          => 'required|date_format:Y-m-d',
    ];


} 