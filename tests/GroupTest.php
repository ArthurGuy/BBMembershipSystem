<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function i_can_view_the_groups_nav_link()
    {
        $this->withoutMiddleware()
            ->visit('/')
            ->see('Groups')
            ->click('Groups')
            ->seePageIs('groups');
    }

    /** @test */
    public function i_can_view_the_groups()
    {
        $role = factory('BB\Entities\Role')->create(['name' => 'new', 'title' => 'New Role', 'description' => 'Group Description']);

        $this->withoutMiddleware()
            ->visit('/groups')
            ->see($role->title)
            ->see($role->description);
    }

    /** @test */
    public function i_can_view_a_single_group()
    {
        $role = factory('BB\Entities\Role')->create(['name' => 'new2', 'title' => 'New Role 2', 'description' => 'Group Description 2']);

        $this->withoutMiddleware()
            ->visit('/groups')
            ->see($role->title)
            ->see($role->description)
            ->click($role->title)
            ->seePageIs('/groups/' . $role->name);
    }


}