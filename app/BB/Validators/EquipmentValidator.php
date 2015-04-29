<?php namespace BB\Validators;

class EquipmentValidator extends FormValidator
{

    /**
     * Validation rules
     *
     * @var array
     */
    protected $rules = [
        'name'               => 'required',
        'manufacturer'       => '',
        'model_number'       => '',
        'serial_number'      => '',
        'colour'             => '',
        'room'               => 'required',
        'detail'             => '',
        'key'                => 'required|unique:equipment,key',
        'device_key'         => '',
        'description'        => '',
        'help_text'          => '',
        'owner_role_id'      => '',
        'requires_induction' => 'boolean',
        'working'            => 'boolean',
        'permaloan'          => 'boolean',
        'permaloan_user_id'  => 'exists:users,id',
        'access_fee'         => 'integer',
        'photo'              => 'image',
        'archive'            => 'boolean',
        'obtained_at'        => 'date_format:Y-m-d|before:tomorrow',
        'removed_at'         => 'date_format:Y-m-d|before:tomorrow',
    ];

} 