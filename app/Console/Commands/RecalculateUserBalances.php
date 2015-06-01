<?php namespace BB\Console\Commands;

use Illuminate\Console\Command;

class RecalculateUserBalances extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'bb:recalculate-balances';

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
    public function fire()
    {
        $users = $this->userRepo->getAll();
        foreach ($users as $user) {
            $memberCreditService = \App::make('\BB\Services\Credit');
            $memberCreditService->setUserId($user->id);
            $memberCreditService->recalculate();
        }
    }


}
