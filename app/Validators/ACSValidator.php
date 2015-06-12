<?php namespace BB\Validators;

class ACSValidator extends FormValidator
{

    protected $rules = [
        'device'  => 'required|max:25|exists:devices,device_id',
        'service' => 'required|in:entry,usage,consumable,shop,status',
        'message' => 'required|in:boot,heartbeat,lookup,start,stop,charge,error',
        'tag'     => 'max:15',
        'time'    => 'max:10|integer',
    ];

} 