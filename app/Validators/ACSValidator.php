<?php namespace BB\Validators;

class ACSValidator extends FormValidator
{

    protected $rules = [
        'device'    => 'required|max:25|exists:devices,device_id',
        'service'   => 'required|in:entry,usage,consumable,shop,status,device-scanner,sensor',
        'message'   => 'required|in:boot,heartbeat,lookup,start,stop,charge,error,update',
        'tag'       => 'max:15',
        'time'      => 'max:10|integer',
        'signature' => '',
        'nonce'     => 'max:12',
    ];

} 