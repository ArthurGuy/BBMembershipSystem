<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{

    public function createMember()
    {
        return \User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);
    }

    public function createActivity()
    {

    }
}