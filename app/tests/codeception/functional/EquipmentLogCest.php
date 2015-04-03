<?php
use \FunctionalTester;

class EquipmentLogCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function adminCanEditLog(FunctionalTester $I)
    {
        $I->am('a laser team member');
        $I->wantTo('make sure I can edit laser logs');

        //Load and login a known admin member
        $user = $I->loginAdminMember();
        $otherUser = User::find(1);

        $I->haveEnabledFilters();

        $I->amOnPage('/equipment/laser');

        $I->see($otherUser->name);

        $I->selectOption('form[name=equipmentLog] select[name=reason]', 'testing');
        $I->click('Update');
    }

    public function memberCantEditOwnLog(FunctionalTester $I)
    {
        $I->am('a laser team member');
        $I->wantTo('make sure I can edit laser logs');

        //Load and login a known admin member
        $user = $I->loginNormalMember();
        $otherUser = User::find(3);

        $I->haveEnabledFilters();

        $I->amOnPage('/equipment/laser');

        $I->see($user->name);

        $I->selectOption('form[name=equipmentLog] select[name=reason]', 'testing');
        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\ValidationException', function() use ($I){
                $I->click('Update');
            })
        );

    }

    public function teamMemberCanEditLog(FunctionalTester $I)
    {
        $I->am('a laser team member');
        $I->wantTo('make sure I can edit laser logs');

        //Load and login a known admin member
        $user = $I->loginLaserTeamMember();
        $otherUser = User::find(1);

        $I->haveEnabledFilters();

        $I->amOnPage('/equipment/laser');

        $I->see($otherUser->name);

        $I->selectOption('form[name=equipmentLog] select[name=reason]', 'testing');
        $I->click('Update');
    }
}