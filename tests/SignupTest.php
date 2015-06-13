<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SignupTest extends TestCase {

    use DatabaseMigrations;
    //use DatabaseTransactions;

    /** @test */
    public function i_can_sign_up_successfully()
    {
        $faker = Faker\Factory::create();

        $firstName = $faker->firstName;
        $email = $faker->email;

        $this->visit('/register')
            ->see('Join')
            ->type($firstName, 'given_name')
            ->type($faker->lastName, 'family_name')
            ->type($email, 'email')
            ->type($faker->password, 'password')
            ->type($faker->streetAddress, 'address[line_1]')
            ->type('BN2 4AA', 'address[postcode]')
            ->type($faker->phoneNumber, 'phone')
            ->type($faker->text, 'emergency_contact')
            ->attach($faker->image(), 'new_profile_photo')
            ->press('Join')
            ->see($firstName)
            ->see('Setting up');

        $this->seeInDatabase('users', ['email' => $email, 'given_name' => $firstName]);
    }
}