<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

Class HomepageTest extends TestCase
{

    /** @test */
    public function i_can_visit_home_page()
    {
        $this->visit('/')
            ->see('Build Brighton')
            ->see('Become a member')
            ->see('Login')
            ->see('www.buildbrighton.com');
    }


}