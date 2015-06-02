<?php

Class ExampleTest extends TestCase {

    public function testHomePage() {

        $this->visit('/')->see('BBMS');
    }

    /** @test */
    public function i_can_login()
    {
        $this->visit('/login')->see('Login')->type('foo@example.com', 'email')->press('Go');
    }

}