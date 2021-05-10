<?php
use BB\Entities\User;

class MemberStorageCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function memberCanVisitStoragePage(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can view the member box page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/storage_boxes');
        $I->canSee('Member Storage Boxes');

        $I->cantSee($user->name);

        //Make sure the message about paying for a box is displayed
        $I->canSee('5 payment');

    }

    public function memberCanClaimBox(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can claim a box');

        //Load and login a known member
        $user = User::find(1);
        $I->amLoggedAs($user);

        //Create a box payment
        $I->haveInDatabase('payments', ['user_id'=>$user->id, 'reason'=>'storage-box', 'source'=>'other', 'amount'=>5.00, 'status'=>'paid']);
        //$user->storage_box_payment_id = $paymentId;
        //$user->save();

        $I->amOnPage('/storage_boxes');

        //Make sure it has seen our payment
        $I->see("Total Paid &pound5");

        //Claim a box
        $I->see('Claim');
        $I->click('Claim');

        //We should end up back on the page
        $I->canSeeCurrentUrlEquals('/storage_boxes');

        //The page should now have our name next to the claimed box
        $I->see($user->name);

        //The box should be ours
        $I->seeInDatabase('storage_boxes', ['user_id' => $user->id]);

        //Add another payment
        $I->haveInDatabase('payments', ['user_id'=>$user->id, 'reason'=>'storage-box', 'source'=>'other', 'amount'=>10.00, 'status'=>'paid']);

        $I->amOnPage('/storage_boxes');

        //Make sure it has seen our new payment
        $I->see("Total Paid &pound15");

        //We should now be able to claim another box
        $I->see('Claim');
        $I->click('Claim');

    }

    public function memberCanReturnBox(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can return a box I own');

        //Load and login a known member
        $user = User::find(1);
        $I->amLoggedAs($user);

        //Setup a box a already claimed
        $box = \BB\Entities\StorageBox::first();
        $box->user_id = $user->id;
        $box->save();


        $I->amOnPage('/storage_boxes');


        //Make sure the db is correct
        $I->seeInDatabase('storage_boxes', ['user_id' => $user->id]);


        //The page should have our name next to the claimed box
        $I->see($user->name);

        $I->click('Return Box');

        //We should be gone from the DB
        $I->dontSeeInDatabase('storage_boxes', ['user_id' => $user->id]);

        $I->cantSee($user->name);


    }
}
