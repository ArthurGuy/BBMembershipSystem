<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function i_can_view_account_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->visit('/account/'.$user->id)
            ->see($user->name)
            ->see($user->email);
    }

    /** @test */
    public function i_cant_view_another_account()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $user2 = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user2->id]);

        $this->actingAs($user);

        $this->get('/account/'.$user2->id)
            ->assertResponseStatus(403);
    }

    /** @test */
    public function i_can_see_accounts_on_member_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $user2 = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user2->id]);

        $this->visit('members')
            ->see($user->name)
            ->see($user2->name);
    }

    /** @test */
    public function guest_cant_see_private_accounts_on_member_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $user2 = factory('BB\Entities\User')->create(['profile_private' => true]);
        factory('BB\Entities\ProfileData')->create(['user_id' => $user2->id]);

        $this->visit('members')
            ->see($user->name)
            ->see($user2->name, true);  //don't see
    }

    /** @test */
    public function member_can_see_private_accounts_on_member_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $user2 = factory('BB\Entities\User')->create(['profile_private' => true]);
        factory('BB\Entities\ProfileData')->create(['user_id' => $user2->id]);

        $this->actingAs($user);

        $this->visit('members')
            ->see($user->name)
            ->see($user2->name);
    }

}