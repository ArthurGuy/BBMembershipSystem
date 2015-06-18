<?php

use BB\Entities\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

Class DevicesTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function i_can_visit_the_devices_page()
    {
        $device = factory('BB\Entities\Device')->create();
        $this->withoutMiddleware()
            ->visit('/devices')
            ->see('Devices')
            ->see($device->name);
    }


}