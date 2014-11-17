<?php namespace BB\Validators;

class ProposalValidator extends FormValidator
{

    protected $rules = [
        'title'               => 'required',
        'description'         => 'required',
        'end_date'            => 'required|date_format:Y-m-d|after:today'
    ];


} 