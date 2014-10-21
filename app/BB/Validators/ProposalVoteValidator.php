<?php namespace BB\Validators;

class ProposalVoteValidator extends FormValidator
{

    protected $rules = [
        'vote'               => 'required|in:+1,0,-1,abstain',
    ];


} 