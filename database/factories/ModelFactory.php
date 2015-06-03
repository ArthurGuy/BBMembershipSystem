<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define('BB\Entities\User', function ($faker) {
    return [
        'given_name' => $faker->firstName,
        'family_name' => $faker->lastName,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
        'hash' => str_random(32),
        'status' => 'active',
        'induction_completed' => false,
        'trusted' => false,
        'key_holder' => false,
        'phone' => false,
    ];
});

$factory->define('BB\Entities\ProfileData', function ($faker) {
    return [
        'user_id' => null,
        'profile_photo' => false,
        'new_profile_photo' => false,
        'profile_photo_private' => false,
        'profile_photo_on_wall' => false,
        'tagline' => $faker->sentence
    ];
});