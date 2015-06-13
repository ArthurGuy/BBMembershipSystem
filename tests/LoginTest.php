<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{

    //use WithoutMiddleware;
    use DatabaseMigrations;
    //use DatabaseTransactions;

    /** @test */
    public function i_can_login()
    {
        $password = str_random(10);
        $user = factory('BB\Entities\User')->create(['password' => $password]);
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->visit('/login')
            ->see('Login')
            ->type($user->email, 'email')
            ->type($password, 'password')
            ->press('Go')
            ->seePageIs('account/'.$user->id)
            ->see($user->given_name);
    }

    /** @test */
    public function unknown_user_cant_login()
    {
        $this->visit('/login')
            ->type('unknown@example.com', 'email')
            ->type('123456789', 'password')
            ->press('Go')
            ->seePageIs('login')
            ->see('Invalid login details');
    }
}