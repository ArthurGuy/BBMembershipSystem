<?php namespace BB\Repo;

class ProposalVoteRepository extends DBRepository {


    /**
     * @var ProposalVote
     */
    protected $model;

    function __construct(\ProposalVote $model)
    {
        $this->model = $model;
    }

    public function getProposalVotes($proposalId)
    {
        return $this->model->where('proposal_id', $proposalId)->get();
    }

    public function getMemberVote($proposalId, $userId)
    {
        return $this->model->where('proposal_id', $proposalId)->where('user_id', $userId)->first();
    }

    public function castVote($proposalId, $userId, $vote)
    {
        $existingVote = $this->getMemberVote($proposalId, $userId);
        if ($existingVote) {
            $memberVote = $existingVote;
        } else {
            $memberVote = new $this->model;
            $memberVote->proposal_id = $proposalId;
            $memberVote->user_id = $userId;
        }
        if ($vote == 'abstain') {
            $memberVote->vote = null;
            $memberVote->abstain = true;
        } else {
            $memberVote->vote = $vote;
            $memberVote->abstain = false;
        }
        $memberVote->save();

    }


} 