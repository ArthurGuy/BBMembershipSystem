<?php

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\DomCrawler\Crawler;

class MemberInductionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function member_can_view_induction_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->get('/account/'.$user->id.'/induction')
            ->seeStatusCode(200)
            ->see('Member Induction')
            ->see('Build Brighton rules');
    }

    /** @test */
    public function member_can_complete_induction_page_form()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->visit('/account/'.$user->id.'/induction')
            ->check('induction_completed')
            ->check('rules_agreed')
            ->press('Confirm')
            ->seePageIs('/account/'.$user->id);
    }

    /** @test */
    public function member_cant_complete_incomplete_induction_page_form()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->visit('/account/'.$user->id.'/induction')
            ->check('rules_agreed')
            ->press('Confirm')
            ->seePageIs('/account/'.$user->id.'/induction');
    }

    /** @test */
    public function member_cant_view_another_induction_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $user2 = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user2->id]);

        $this->actingAs($user);

        $this->get('/account/'.$user2->id.'/induction')
            ->assertResponseStatus(403);
    }

    /** @test */
    public function comms_member_can_view_inductions_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);
        $role = BB\Entities\Role::findByName('comms');
        $role->users()->attach($user);

        $this->actingAs($user);

        $this->visit('/member_inductions')
            ->assertResponseStatus(200);
    }

    /** @test */
    public function comms_member_can_complete_inductions_page_form()
    {
        //create a user who has completed the form
        $otherUser = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $otherUser->id]);
        $role = BB\Entities\Role::findByName('comms');
        $role->users()->attach($otherUser);

        $otherUser->induction_completed = true;
        $otherUser->rules_agreed = Carbon::now();
        $otherUser->save();


        //comms member can view the page and approve
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);
        $role = BB\Entities\Role::findByName('comms');
        $role->users()->attach($user);

        $this->actingAs($user);

        $this->visit('/member_inductions')
            ->see($otherUser->email)    //check email as the name wont render and test correctly if it has funny characters
            ->press('Confirm member induction')
            //->dontSee($otherUser->email);
            ->seePageIs('/member_inductions');
    }

    /** @test */
    public function ordinary_member_cant_view_inductions_page()
    {
        $user = factory('BB\Entities\User')->create();
        factory('BB\Entities\ProfileData')->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->get('/member_inductions')
            ->assertResponseStatus(403);
    }
}
