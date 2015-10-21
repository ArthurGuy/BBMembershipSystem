<?php

class EquipmentCest
{
    public function _before(FunctionalTester $I)
    {
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

        $I->amOnPage('/equipment/create');
        $I->canSeeResponseCodeIs(403);
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
        $equipmentSlug = substr($this->faker->slug, 0, 10);
        $I->fillField('Name', $equipmentName);
        $I->fillField('Slug', $equipmentSlug);
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
        $slug = substr($this->faker->slug, 0, 10);

        //First item
        $I->click('Record a new item');
        $I->fillField('Name', $name);
        $I->fillField('Slug', $slug);
        $I->click('Save');
        $I->seeCurrentUrlEquals('/equipment/'.$slug.'/edit');

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

    public function canRecordPhoto(FunctionalTester $I)
    {
        $I->am('a developer');
        $I->wantTo('ensure photos get recorded in the db');

        $equipment = \BB\Entities\Equipment::findOrFail(2);
        $I->assertTrue(is_array($equipment->photos), "The photos element is an array");
        $I->assertEquals(0, count($equipment->photos), 'Should have no photos');

        $equipment->addPhoto('foo.png');
        $equipment = \BB\Entities\Equipment::findOrFail(2);
        $I->assertEquals(1, count($equipment->photos), 'Should have 1 photo');

        $equipment->addPhoto('bar.png');
        $equipment = \BB\Entities\Equipment::findOrFail(2);
        $I->assertEquals(2, count($equipment->photos), 'Should have 2 photos');
        $I->assertEquals([['path'=>'foo.png'],['path'=>'bar.png']], $equipment->photos, 'Should contain photo paths');
    }

    public function canFetchPhoto(FunctionalTester $I)
    {
        $I->am('a developer');
        $I->wantTo('ensure photos can be fetched');

        $equipment = \BB\Entities\Equipment::findOrFail(2);
        $equipment->addPhoto('foo.png');

        $equipment = \BB\Entities\Equipment::findOrFail(2);
        $I->assertEquals($equipment->getPhotoBasePath().'foo.png', $equipment->getPhotoPath(0));
    }
}