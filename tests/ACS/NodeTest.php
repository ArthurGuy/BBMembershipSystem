<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NodeTest extends TestCase
{
    /**
     * @test
     */
    public function boot_endpoint_updates_db()
    {
        $node = factory('BB\Entities\ACSNode')->create();
        //$this->get('/acs/test', ['Accept' => 'application/json', 'ApiKey' => $node->api_key]);

        //$this->seeInDatabase('acs_nodes', ['api_key' => $node->api_key, 'last_boot' => '']);
    }
}
