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

    public function getAll()
    {
        return $this->model->orderBy('end_date', 'desc')->get();
    }

    public function getUnprocessedFinishedProposals()
    {
        return $this->model->where('end_date', '<', Carbon::now()->format('Y-m-d'))->where('processed', false)->get();
    }

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

    public function create($data)
    {
        return $this->model->create($data);
    }
}