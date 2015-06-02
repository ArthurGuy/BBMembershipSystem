<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{

    //use WithoutMiddleware;

    /** @test */
    public function i_can_login()
    {
        $this->visit('/login')
            ->see('Login')
            ->type('jondoe@example.com', 'email')
            ->type('123456789', 'password')
            ->press('Go')
            ->seePageIs('account/10');
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