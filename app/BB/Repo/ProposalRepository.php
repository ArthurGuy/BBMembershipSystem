<?php namespace BB\Repo;

use Carbon\Carbon;

class ProposalRepository extends DBRepository {


    /**
     * @var Proposal
     */
    protected $model;

    function __construct(\Proposal $model)
    {
        $this->model = $model;
    }

    /**
     * Return all the proposals with the newest ones by closing date first
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->orderBy('end_date', 'desc')->get();
    }

    /**
     * Return a list of proposals that have finished but haven't had their results completed
     * @return mixed
     */
    public function getUnprocessedFinishedProposals()
    {
        return $this->model->where('end_date', '<', Carbon::now()->format('Y-m-d'))->where('processed', false)->get();
    }

    /**
     * Fill in the results for a proposal
     * @param $proposalId
     * @param $result
     * @param $votesCast
     * @param $for
     * @param $against
     * @param $neutral
     * @param $abstentions
     * @param $quorum
     */
    public function setResults($proposalId, $result, $votesCast, $for, $against, $neutral, $abstentions, $quorum)
    {
        $proposal = $this->getById($proposalId);
        $proposal->result = $result;
        $proposal->votes_cast = $votesCast;
        $proposal->votes_for = $for;
        $proposal->votes_against = $against;
        $proposal->abstentions = $abstentions;
        $proposal->quorum = $quorum;
        $proposal->processed = true;
        $proposal->save();
    }

    /**
     * Is the specific proposal available for editing
     * @param $proposalId
     * @return mixed
     */
    public function canEdit($proposalId)
    {
        //Possibly expand this to allow editing on proposals that have been started but received no votes
        $proposal = $this->getById($proposalId);
        return !$proposal->hasStarted();
    }
}