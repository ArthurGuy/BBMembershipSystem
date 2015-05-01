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
        'key'                => 'required|alpha_dash|unique:equipment,key',
        'device_key'         => '',
        'description'        => '',
        'help_text'          => '',
        'owner_role_id'      => '',
        'requires_induction' => 'boolean',
        'induction_category' => 'required_if:requires_induction,1|alpha_dash',
        'working'            => 'boolean',
        'permaloan'          => 'boolean',
        'permaloan_user_id'  => 'exists:users,id|required_id:permaloan,1',
        'access_fee'         => 'integer',
        'photo'              => 'image',
        'archive'            => 'boolean',
        'obtained_at'        => 'date_format:Y-m-d|before:tomorrow',
        'removed_at'         => 'date_format:Y-m-d|before:tomorrow',
    ];

    //During an update these rules will override the ones above
    protected $updateRules = [
        'key'                => '',
    ];

} 