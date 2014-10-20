<?php 

class ProposalVote extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proposal_votes';

    public function proposal()
    {
        return $this->belongsTo('Proposal');
    }

    public function member()
    {
        return $this->belongsTo('User', 'user_id');
    }

} 