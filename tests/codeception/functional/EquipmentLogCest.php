<?php
use BB\Entities\User;

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

        $I->amOnPage('/equipment/laser');

        $I->see($user->name);

        $I->selectOption('form[name=equipmentLog] select[name=reason]', 'testing');

        $I->seeExceptionThrown('BB\Exceptions\ValidationException', function($I) {
            $I->click('Update');
        });

    }

    public function teamMemberCanEditLog(FunctionalTester $I)
    {
        $I->am('a laser team member');
        $I->wantTo('make sure I can edit laser logs');

        //Load and login a known admin member
        $user = $I->loginLaserTeamMember();
        $otherUser = User::find(1);



        $I->amOnPage('/equipment/laser');

        $I->see($otherUser->name);

        $I->selectOption('form[name=equipmentLog] select[name=reason]', 'testing');
        $I->click('Update');
    }
}