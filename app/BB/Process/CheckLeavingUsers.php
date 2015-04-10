<?php namespace BB\Process;

use BB\Entities\User;
use BB\Repo\UserRepository;
use Carbon\Carbon;

class CheckLeavingUsers
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function run()
    {

        $today = new Carbon();

        //Fetch and check over active users which have a status of leaving
        $users = User::leaving()->notSpecialCase()->get();
        foreach ($users as $user) {
            if ($user->subscription_expires->lt($today)) {
                //User has passed their expiry date

                //set the status to left and active to false
                $this->userRepository->memberLeft($user->id);

                //an email will be sent by the user observer
            }

        }
    }

} 