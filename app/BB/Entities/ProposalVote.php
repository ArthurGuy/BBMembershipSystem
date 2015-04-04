<?php namespace BB\Entities;

class ProposalVote extends \Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proposal_votes';

    public function proposal()
    {
        return $this->belongsTo('\BB\Entities\Proposal');
    }

    public function member()
    {
        return $this->belongsTo('\BB\Entities\User', 'user_id');
    }

} 