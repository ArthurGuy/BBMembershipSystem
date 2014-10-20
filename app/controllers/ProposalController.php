<?php 

class ProposalController extends \BaseController {


    /**
     * @var \BB\Repo\ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var \BB\Repo\ProposalVoteRepository
     */
    private $proposalVoteRepository;

    function __construct(\BB\Repo\ProposalRepository $proposalRepository, \BB\Repo\ProposalVoteRepository $proposalVoteRepository)
    {
        $this->proposalRepository = $proposalRepository;
        $this->proposalVoteRepository = $proposalVoteRepository;
    }

    /**
     * Display the proposals
     *
     * @return Response
     */
    public function index()
    {
        $proposals = $this->proposalRepository->getAll();

        return View::make('proposals.index')->with('proposals', $proposals);
    }

    public function show($proposalId)
    {
        $proposal = $this->proposalRepository->getById($proposalId);
        $memberVotes = $this->proposalVoteRepository->getProposalVotes($proposalId);

        $memberVote = $this->proposalVoteRepository->getMemberVote($proposalId, Auth::user()->id);

        return View::make('proposals.show')
            ->with('proposal', $proposal)
            ->with('memberVotes', $memberVotes)
            ->with('memberVote', $memberVote);
    }

    public function vote($proposalId)
    {
        //dd(Request::all());


        //validation

        $proposal = $this->proposalRepository->getById($proposalId);
        if (!$proposal->isOpen()) {
            throw new \BB\Exceptions\ValidationException();
        }

        $this->proposalVoteRepository->castVote($proposalId, Auth::user()->id, Request::get('vote'));

        Notification::success("Vote cast");
        return Redirect::back();
    }
} 