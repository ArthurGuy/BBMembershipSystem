<?php namespace BB\Console\Commands;

use Illuminate\Console\Command;

class CalculateProposalVotes extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'bb:calculate-proposal-votes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the vote tallies stored on the proposal records.';
    /**
     * @var \BB\Repo\ProposalRepository
     */
    private $proposalRepository;

    /**
     * @var \BB\Repo\ProposalVoteRepository
     */
    private $proposalVoteRepository;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->proposalRepository = \App::make('\BB\Repo\ProposalRepository');
        $this->proposalVoteRepository = \App::make('\BB\Repo\ProposalVoteRepository');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $proposals = $this->proposalRepository->getUnprocessedFinishedProposals();
        foreach ($proposals as $proposal) {
            $this->info($proposal->title);
            $votes = $this->proposalVoteRepository->getProposalVotes($proposal->id);
            $for = 0;
            $against = 0;
            $neutral = 0;
            $abstain = 0;
            $votesCast = 0;
            foreach ($votes as $vote) {
                if ($vote->abstain) {
                    $abstain++;
                } else {
                    if ($vote->vote == '+1') {
                        $for++;
                    } elseif ($vote->vote == '0') {
                        $neutral++;
                    } elseif ($vote->vote == '-1') {
                        $against++;
                    }
                    $votesCast++;
                }
            }
            $this->info("For: " . $for);
            $this->info("Neutral: " . $neutral);
            $this->info("Against: " . $against);
            $this->info("Abstain: " . $abstain);

            $result = $for - $against;

            $quorum = true;

            $this->proposalRepository->setResults($proposal->id, $result, $votesCast, $for, $against, $neutral, $abstain, $quorum);

        }
    }


}
