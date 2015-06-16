<?php namespace BB\Console\Commands;

use Illuminate\Console\Command;

class RecalculateUserBalances extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'bb:update-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate User Balances';

    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepo;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepo = \App::make('\BB\Repo\UserRepository');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = $this->userRepo->getAll();

        $this->output->progressStart($users->count());

        foreach ($users as $user) {
            $memberCreditService = \App::make('\BB\Services\Credit');
            $memberCreditService->setUserId($user->id);
            $memberCreditService->recalculate();

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }


}
