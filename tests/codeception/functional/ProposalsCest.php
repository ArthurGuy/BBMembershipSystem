<?php
use BB\Entities\User;
use Carbon\Carbon;
use \FunctionalTester;

class ProposalsCest
{

    // tests
    public function memberCanVisitProposalList(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can view the proposals page');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSee('Proposals');

    }

    public function guestCantVisitProposalList(FunctionalTester $I)
    {
        $I->am('a guest');
        $I->wantTo('make sure I can\'t view the proposals page');

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSeeCurrentUrlEquals('/login');
    }

    public function memberCanViewVoteOnActiveProposal(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I can view and vote on a proposal');

        //Create a proposal that's currently open
        $startDate = Carbon::now()->subDay()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>2, 'title'=>'Proposal 2', 'description'=>'Demo Description', 'user_id'=>'1', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSee('Proposals');

        //Goto the proposal page
        $I->click('Proposal 2');

        $I->canSee('Proposal 2');
        $I->canSee('Voting is Open');

        //Select the +1 vote option
        $I->selectOption('[name=vote]', '+1');

        $I->click('Vote');

        $I->canSeeInDatabase('proposal_votes', ['proposal_id'=>2, 'user_id'=>$user->id, 'vote'=>'+1']);
    }

    public function memberCantVoteOnClosedProposal(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant vote on a closed proposal');

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSee('Proposals');

        //Goto the proposal page
        $I->click('Proposal 1');

        $I->canSee('Proposal 1');
        $I->canSee('Voting has Closed');
    }

    public function memberCantVoteOnNotStartedProposal(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant vote on a proposal that hasnt started yet');

        //Create a proposal starting in the future
        $startDate = Carbon::now()->addDay()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>3, 'title'=>'Proposal 3', 'description'=>'Demo Description', 'user_id'=>'1', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSee('Proposals');

        //Goto the proposal page
        $I->click('Proposal 3');

        $I->canSee('Proposal 3');
        $I->canSee('Voting hasn\'t started yet');

    }

    public function memberCantSneakyVoteOnNotStartedProposal(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cant sneakily vote on a proposal that hasnt started yet');

        //Create a proposal starting in the future
        $startDate = Carbon::now()->addDay()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>4, 'title'=>'Proposal 4', 'description'=>'Demo Description', 'user_id'=>'1', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //I can see the menu item
        $I->amOnPage('/proposals');
        $I->canSee('Proposal 4');

        //Confirm that posting directly generates a validation exception
        $I->sendPOST('/proposals/4', ['vote'=>'+1']);

        $I->assertTrue(\Notification::hasMessage());
        $I->assertEquals('The proposal isn\'t open for voting', \Notification::getMessage());
    }

    public function adminCanEditUnstartedProposal(FunctionalTester $I)
    {
        $I->am('an admin');
        $I->wantTo('make sure I can edit a proposal that hasnt been started');

        //Create a proposal that's currently open
        $startDate = Carbon::now()->addDays(1)->format('Y-m-d');
        $endDate = Carbon::now()->addDays(5)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>2, 'title'=>'Proposal 2', 'description'=>'Demo Description', 'user_id'=>'3', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->amOnPage('/proposals/2');

        //I can visit the edit page
        $I->click('Edit Proposal');
        $I->canSeeCurrentUrlEquals('/proposals/2/edit');

        $I->click('Update');
        $I->canSeeCurrentUrlEquals('/proposals');

    }

    public function memberCantEditUnstartedProposal(FunctionalTester $I)
    {
        $I->am('a member');
        $I->wantTo('make sure I cannt edit a proposal');

        //Create a proposal that's currently open
        $startDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $endDate = Carbon::now()->addDays(5)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>5, 'title'=>'Proposal 2', 'description'=>'Demo Description', 'user_id'=>'3', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(1);
        Auth::login($user);

        //Make sure I cant visit the edit page
        $I->amOnPage('proposals/5/edit');
        $I->canSeeResponseCodeIs(403);

    }

    public function adminCantEditStartedProposal(FunctionalTester $I)
    {
        $I->am('an admin');
        $I->wantTo('make sure I cannt edit a proposal thats been started');

        //Create a proposal that's currently open
        $startDate = Carbon::now()->subDays(2)->format('Y-m-d');
        $endDate = Carbon::now()->addDays(2)->format('Y-m-d');
        $I->haveInDatabase('proposals', ['id'=>2, 'title'=>'Proposal 2', 'description'=>'Demo Description', 'user_id'=>'3', 'start_date'=>$startDate, 'end_date'=>$endDate]);

        //Load and login a known member
        $user = User::find(3);
        Auth::login($user);

        $I->amOnPage('/proposals/2');

        //I can visit the edit page
        $I->cantSee('Edit Proposal');

    }
}