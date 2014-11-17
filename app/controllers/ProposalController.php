<?php

use Carbon\Carbon;

class ProposalController extends \BaseController {


    /**
     * @var \BB\Repo\ProposalRepository
     */
    private $proposalRepository;
    /**
     * @var \BB\Repo\ProposalVoteRepository
     */
    private $proposalVoteRepository;
    /**
     * @var \BB\Validators\ProposalVoteValidator
     */
    private $proposalVoteValidator;
    /**
     * @var \BB\Validators\ProposalValidator
     */
    private $proposalValidator;

    function __construct(\BB\Repo\ProposalRepository $proposalRepository,
        \BB\Repo\ProposalVoteRepository $proposalVoteRepository,
        \BB\Validators\ProposalVoteValidator $proposalVoteValidator,
        \BB\Validators\ProposalValidator $proposalValidator)
    {
        $this->proposalRepository = $proposalRepository;
        $this->proposalVoteRepository = $proposalVoteRepository;
        $this->proposalVoteValidator = $proposalVoteValidator;
        $this->proposalValidator = $proposalValidator;
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
        //validation
        $this->proposalVoteValidator->validate(Request::all());

        $proposal = $this->proposalRepository->getById($proposalId);
        if (!$proposal->isOpen()) {
            throw new \BB\Exceptions\ValidationException();
        }

        $this->proposalVoteRepository->castVote($proposalId, Auth::user()->id, Request::get('vote'));

        Notification::success("Vote cast");
        return Redirect::back();
    }

    public function create()
    {
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');
        return View::make('proposals.create')->with('endDate', $endDate);
    }

    public function store()
    {
        $this->proposalValidator->validate(Request::all());

        $data = Request::only('title', 'description', 'end_date');
        $data['user_id'] = \Auth::user()->id;
        $this->proposalRepository->create($data);

        Notification::success("Proposal created");
        return Redirect::route('proposals.index');
    }


} 