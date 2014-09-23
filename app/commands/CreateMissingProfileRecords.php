<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateMissingProfileRecords extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bb:create-profiles';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create missing profile records';
    /**
     * @var \BB\Repo\UserRepository
     */
    private $userRepo;

    /**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
        $this->userRepo = App::make('\BB\Repo\UserRepository');
        $this->profileRepo = App::make('\BB\Repo\ProfileDataRepository');
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
            try {
                $this->profileRepo->getUserProfile($user->id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $this->profileRepo->createProfile($user->id);
            }
        }
	}


}
