<?php
use BB\Entities\User;
use \FunctionalTester;

class MemberCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function memberCanVisitBalancePage(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can view my balance page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->haveEnabledFilters();

        //I can see the menu item
        $I->amOnPage('/account/'.$user->id.'/balance');
        $I->canSee('Build Brighton Balance');

    }

    public function memberCantVisitAnotherBalancePage(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant view someone elses balance page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->haveEnabledFilters();

        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\AuthenticationException', function() use ($I){
                    $I->amOnPage('/account/3/balance');
                })
        );

        //\PHPUnit_Framework_TestCase::setExpectedException('BB\Exceptions\AuthenticationException');
        //$I->amOnPage('/account/3/balance');

    }

    public function guestCantVisitSomeonesBalancePage(FunctionalTester $I)
    {
        $I->am('a guest');
        $I->wantTo('make sure I can\'t view a balance page');

        $I->haveEnabledFilters();

        //I can see the menu item
        $I->amOnPage('/account/1/balance');
        $I->canSeeCurrentUrlEquals('/login');
    }

    public function memberCantAddCash(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant add cash payments to my account');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->haveEnabledFilters();

        //I cant see option
        $I->amOnPage('/account/'.$user->id.'');
        $I->cantSee('Record a cash balance payment');

        //Make sure they cant post payment directly
        //Confirm that posting directly generates a validation exception
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\AuthenticationException', function() use ($I, $user){
                $I->sendPOST('/account/'.$user->id.'/payment/cash/create', ['reason'=>'balance', 'amount'=>4.69, 'return_path'=>'/']);
            })
        );

        //One final check
        $I->cantSeeInDatabase('payments', ['amount'=>4.69]);
    }

    public function memberCanLeave(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('leave build brighton');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->haveEnabledFilters();

        $I->amOnPage('/account/'.$user->id.'');

        $I->canSee('Active');

        $I->click("Leave Build Brighton");

        $I->canSee('Leaving');
    }
}