<?php
use BB\Entities\User;
use Carbon\Carbon;

class ExpensesCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function nonMemberCantViewExpenses(FunctionalTester $I)
    {
        $I->am('a guest');
        $I->wantTo('make sure I cant view the list of expenses');

        //Create a proposal that's currently open
        $I->haveInDatabase('expenses', ['id'=>1, 'category'=>'consumables', 'description'=>'Sample Description', 'user_id'=>'3', 'amount' => 1234, 'expense_date'=>Carbon::now()]);

        $I->amOnPage('/expenses');

        $I->canSeeCurrentUrlEquals('/login');

    }

    public function memberCanViewExpenses(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can view the list of expenses');

        //Create a proposal that's currently open
        $I->haveInDatabase('expenses', ['id'=>2, 'category'=>'consumables', 'description'=>'Sample Description', 'user_id'=>'3', 'amount' => 1234, 'expense_date'=>Carbon::now()]);

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        $I->amOnPage('/expenses');

        $I->canSee('Expenses');
        $I->canSee('Sample Description');

        $I->cantSee('Approve');
        $I->cantSee('Decline');

    }

    public function adminCanViewApproveExpenses(FunctionalTester $I)
    {
        $I->am('an admin');
        $I->wantTo('make sure I can view the list of expenses and approve them');

        //Create a proposal that's currently open
        $I->haveInDatabase('expenses', ['id'=>3, 'category'=>'consumables', 'description'=>'Sample Description', 'user_id'=>'3', 'amount' => 1234, 'expense_date'=>Carbon::now()]);

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->amOnPage('/expenses');

        $I->canSee('Expenses');
        $I->canSee('Sample Description');

        $I->cantSee('Approved by');
        $I->click('Approve');
        $I->canSee('Approved by');

    }

    public function adminCanDeclineExpenses(FunctionalTester $I)
    {
        $I->am('an admin');
        $I->wantTo('make sure I can decline an expense');

        //Create a proposal that's currently open
        $I->haveInDatabase('expenses', ['id'=>4, 'category'=>'consumables', 'description'=>'Another Description', 'user_id'=>'3', 'amount' => 1234, 'expense_date'=>Carbon::now()]);

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->amOnPage('/expenses');

        $I->canSee('Expenses');
        $I->canSee('Another Description');

        $I->cantSee('Declined by');
        $I->click('Decline');
        $I->canSee('Declined by');

    }
/*
    public function memberCanCreateExpense(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('submit a new expense');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);


        $payloadData = [
            "category" => "consumables",
            "description" => "item description",
            "amount" => "8554"
        ];

        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
        $I->sendPOST('/expenses', $payloadData);
        //$I->sendAjaxPostRequest('/expenses', $payloadData);
        $I->seeResponseCodeIs(201);

        $I->seeInDatabase('expenses', ['category' => 'consumables', 'amount' => '8554', 'user_id' => 1]);
    }
*/
}