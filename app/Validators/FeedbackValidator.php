<?php namespace BB\Validators;

class FeedbackValidator extends FormValidator
{

    protected $rules = [
        'comments' => 'required',
    ];


} 