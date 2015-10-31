<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StatusTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function status_endpoint_returns_details_for_valid_key_fob()
    {
        $user = factory('BB\Entities\User')->create();
        $tag = factory('BB\Entities\KeyFob')->create(['user_id' => $user->id]);
        $this->get('/acs/status/' . $tag->key_id, ['Accept' => 'application/json']);
        $this->assertResponseStatus(200);
        $this->seeJson(['name' => $user->name]);
    }

    /**
     * @test
     */
    public function status_endpoint_returns_details_for_partial_key_fob()
    {
        $user = factory('BB\Entities\User')->create();
        $tag = factory('BB\Entities\KeyFob')->create(['user_id' => $user->id]);

        $keyFob = substr($tag->key_id, 0, 10);
        $keyFob{0} = 0;

        //Confirm the partial keyfob is in fact a partial keyfob
        $this->assertEquals('0', substr($keyFob, 0, 1));
        $this->assertEquals(10, strlen($keyFob));

        $this->get('/acs/status/' . $keyFob, ['Accept' => 'application/json']);
        $this->assertResponseStatus(200);
        $this->seeJson(['name' => $user->name]);
    }

    /**
     * @test
     */
    public function status_endpoint_returns_404_for_invalid_key_fob()
    {
        $this->get('/acs/status/' . str_random(12), ['Accept' => 'application/json']);
        $this->assertResponseStatus(404);
    }
}
