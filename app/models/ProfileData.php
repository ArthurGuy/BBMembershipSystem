<?php 

class ProfileData extends Eloquent {

    protected $fillable = [
        'twitter', 'facebook', 'google_plus', 'github', 'website', 'tagline', 'description', 'skills_array'
    ];

    protected $table = 'profile_data';

} 