<?php
use \FunctionalTester;

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

        $I->haveEnabledFilters();

        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\AuthenticationException', function() use ($I){
                $I->amOnPage('/payments');
            })
        );
    }

    public function adminCanVisitPaymentPage(FunctionalTester $I)
    {
        $I->am('a an admin');
        $I->wantTo('make sure I can view the payments page');

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->haveEnabledFilters();

        $I->amOnPage('/payments');
        $I->seeCurrentUrlEquals('/payments');
        $I->see('Payments');
    }
}