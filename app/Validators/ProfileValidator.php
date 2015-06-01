<?php namespace BB\Validators;

class ProfileValidator extends FormValidator
{

    protected $rules = [
        'twitter'               => 'max:50',
        'facebook'              => 'max:50',
        'google_plus'           => 'max:50',
        'github'                => 'max:50',
        'irc'                   => 'max:50',
        'website'               => 'url|max:100',
        'tagline'               => 'max:250',
        'description'           => '',
        'skills'                => 'array',
        'new_profile_photo'     => 'required|image',
        'profile_photo_private' => 'boolean',
    ];

    //During an update these rules will override the ones above
    protected $updateRules = [
        'new_profile_photo' => 'image',
    ];


    protected $adminOverride = [
        'new_profile_photo' => 'image',
    ];

} 