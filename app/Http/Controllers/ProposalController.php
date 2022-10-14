<?php namespace BB\Http\Controllers;

use Carbon\Carbon;

class ProposalController extends Controller
{


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

        return \View::make('proposals.index')->with('proposals', $proposals);
    }

    public function show($proposalId)
    {
        $proposal = $this->proposalRepository->getById($proposalId);
        $memberVotes = $this->proposalVoteRepository->getProposalVotes($proposalId);

        $memberVote = $this->proposalVoteRepository->getMemberVote($proposalId, \Auth::user()->id);

        return \View::make('proposals.show')
            ->with('proposal', $proposal)
            ->with('memberVotes', $memberVotes)
            ->with('memberVote', $memberVote);
    }

    public function vote($proposalId)
    {
        if (\Auth::user()->hasRole('storage-box-user')) {
            throw new \BB\Exceptions\ValidationException("Your account cannot vote on proposals");
        }

        //validation
        $this->proposalVoteValidator->validate(\Request::all());

        $proposal = $this->proposalRepository->getById($proposalId);
        if ( ! $proposal->isOpen()) {
            throw new \BB\Exceptions\ValidationException("The proposal isn't open for voting");
        }

        $this->proposalVoteRepository->castVote($proposalId, \Auth::user()->id, \Request::get('vote'));

        \Notification::success("Vote cast");
        return \Redirect::back();
    }

    public function create()
    {
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');
        return \View::make('proposals.create')->with('startDate', $startDate)->with('endDate', $endDate);
    }

    public function store()
    {
        $this->proposalValidator->validate(\Request::all());

        $data = \Request::only('title', 'description', 'start_date', 'end_date');
        $data['user_id'] = \Auth::user()->id;
        $this->proposalRepository->create($data);

        \Notification::success("Proposal created");
        return \Redirect::route('proposals.index');
    }

    public function edit($proposalId)
    {
        if ( ! $this->proposalRepository->canEdit($proposalId)) {
            throw new \BB\Exceptions\ValidationException("The proposal can no longer be edited");
        }
        $proposal = $this->proposalRepository->getById($proposalId);

        return \View::make('proposals.edit')->with('proposal', $proposal);
    }

    public function update($proposalId)
    {
        $this->proposalValidator->validate(\Request::all(), $proposalId);
        $data = \Request::only('title', 'description', 'start_date', 'end_date');

        $this->proposalRepository->update($proposalId, $data);

        \Notification::success("Proposal updated");
        return \Redirect::route('proposals.index');
    }
}
