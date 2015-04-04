<?php
use \FunctionalTester;

class RolesCest
{

    public function adminCanVisitRolesPage(FunctionalTester $I)
    {
        $I->am('an admin');
        $I->wantTo('make sure I can view the roles page');

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->haveEnabledFilters();

        //I can see the menu item
        $I->amOnPage('/roles');
        $I->canSee('Member Roles');
    }

    public function memberCantVisitRolesPage(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can\'t view the roles page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->haveEnabledFilters();

        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\AuthenticationException', function() use ($I){
                $I->amOnPage('/roles');
            })
        );
    }

    public function guestCantVisitRolesPage(FunctionalTester $I)
    {
        $I->am('a guest');
        $I->wantTo('make sure I can\'t view the roles page');

        $I->haveEnabledFilters();

        //I can see the menu item
        $I->amOnPage('/roles');
        $I->canSeeCurrentUrlEquals('/login');
    }
}