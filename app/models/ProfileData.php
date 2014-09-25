<?php 

class ProfileData extends Eloquent {

    protected $fillable = [
        'twitter', 'facebook', 'google_plus', 'github', 'irc', 'website', 'tagline', 'description', 'skills_array'
    ];

    protected $table = 'profile_data';

} 