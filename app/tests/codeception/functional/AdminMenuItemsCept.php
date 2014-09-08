<?php 
$I = new FunctionalTester($scenario);

$I->am('an admin user');
$I->wantTo('see the admin menu items');

//Create an admin user
$user = User::create(['given_name' => 'Test', 'family_name' => 'Person', 'email' => 'testperson@example.com']);
$role = Role::create(['name'=>'admin']);
$user->assignRole($role);

Auth::login($user);

$I->amOnPage('/');

$I->canSee('Admin');