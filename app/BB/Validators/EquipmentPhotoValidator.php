<?php namespace BB\Validators;

class EquipmentPhotoValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'photo'              => 'image',
    ];

} 