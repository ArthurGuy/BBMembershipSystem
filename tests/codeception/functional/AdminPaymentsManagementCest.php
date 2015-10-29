<?php
use BB\Entities\Role;
use BB\Entities\User;

class AdminPaymentsManagementCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function memberCantVisitPaymentPage(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant view the payments page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->amOnPage('/payments');

        $I->canSeeResponseCodeIs(403);
    }

    public function financeMemberCanVisitPaymentPage(FunctionalTester $I)
    {
        $I->am('a member of the finance group');
        $I->wantTo('make sure I can view the payments page');

        //Load and login a known member
        $user = User::find(3);

        $role = Role::findByName('finance');
        $role->users()->attach($user->id);

        Auth::login($user);

        $I->amOnPage('/payments');
        $I->seeCurrentUrlEquals('/payments');
        $I->see('Payments');
    }
}