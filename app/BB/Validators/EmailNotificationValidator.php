<?php namespace BB\Validators;

class EmailNotificationValidator extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'subject' => 'required',
        'message' => 'required',
    ];

} 