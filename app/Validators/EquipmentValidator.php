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
        'slug'               => 'required|alpha_dash|unique:equipment,slug',
        'device_key'         => 'exists:acs_nodes,device_id',
        'description'        => '',
        'help_text'          => '',
        'managing_role_id'   => 'exists:roles,id',
        'requires_induction' => 'boolean',
        'induction_category' => 'required_if:requires_induction,1|alpha_dash',
        'working'            => 'boolean',
        'permaloan'          => 'boolean',
        'permaloan_user_id'  => 'exists:users,id|required_if:permaloan,1',
        'access_fee'         => 'integer',
        'usage_cost'         => 'numeric',
        'usage_cost_per'     => 'in:hour,gram,page',
        'photo'              => 'image',
        'archive'            => 'boolean',
        'asset_tag_id'       => 'unique:equipment,asset_tag_id',
        'obtained_at'        => 'date_format:Y-m-d|before:tomorrow',
        'removed_at'         => 'date_format:Y-m-d|before:tomorrow',
    ];

    //During an update these rules will override the ones above
    protected $updateRules = [
        'slug'               => '',
        'asset_tag_id'       => 'unique:equipment,asset_tag_id,{id}',
    ];

} 
