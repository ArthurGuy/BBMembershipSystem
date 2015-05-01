<?php
use \FunctionalTester;

class EquipmentCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveEnabledFilters();
        $this->faker = \Faker\Factory::create();
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function memberCantCreateEntry(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant create an equipment entry');

        //Load and login a known member
        $I->loginNormalMember();

        $I->amOnPage('/equipment');
        $I->cantSee('Record a new item');

        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\AuthenticationException', function() use ($I){
                $I->amOnPage('/equipment/create');
            })
        );
    }

    public function equipmentTeamMemberCanCreateEntry(FunctionalTester $I)
    {
        $I->am('an equipment team member');
        $I->wantTo('make sure I can create an equipment entry');

        //Load and login a known member
        $I->loginEquipmentTeamMember();

        $I->amOnPage('/equipment');
        $I->canSee('Record a new item');

        $I->click('Record a new item');
        $I->seeCurrentUrlEquals('/equipment/create');

        $equipmentName = $this->faker->word;
        $I->fillField('Name', $equipmentName);
        $I->fillField('Key', $this->faker->slug);
        $I->click('Save');

        $I->see($equipmentName);

    }


    public function cantCreateDuplicateKeyEntry(FunctionalTester $I)
    {
        $I->am('an equipment team member');
        $I->wantTo('make sure I cant create duplicate entries');

        //Load and login a known member
        $I->loginEquipmentTeamMember();

        $I->amOnPage('/equipment');
        $I->canSee('Record a new item');

        $name = $this->faker->word;
        $slug = $this->faker->slug;

        //First item
        $I->click('Record a new item');
        $I->fillField('Name', $name);
        $I->fillField('Key', $slug);
        $I->click('Save');
        $I->seeCurrentUrlEquals('/equipment');

        /*
        //Second item
        $I->click('Record a new item');
        $I->fillField('Name', $name);
        $I->fillField('Key', $slug);

        $I->assertTrue(
            $I->seeExceptionThrown('BB\Exceptions\FormValidationException', function() use ($I){
                $I->click('Save');
            })
        );
        */
    }
}