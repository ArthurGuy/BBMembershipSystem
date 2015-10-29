<?php

use BB\Exceptions\AuthenticationException;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FinanceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function guest_cant_view_payments_page()
    {
        $this->get('/payments')
            ->assertRedirectedToRoute('login');
    }

    /** @test */
    public function member_cant_view_payments_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->get('/payments')
            ->seeStatusCode(403);
    }

    /** @test */
    public function finance_member_can_view_payments_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);
        $role = BB\Entities\Role::findByName('finance');
        $role->users()->attach($user);

        $this->actingAs($user);

        $this->get('/payments')
            ->assertResponseStatus(200);
    }

}