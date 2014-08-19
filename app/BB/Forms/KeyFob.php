<?php namespace BB\Forms;

class KeyFob extends FormValidator {

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'key_id' => 'required|unique:key_fobs|min:8|max:12',
    ];

} 