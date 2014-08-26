<?php namespace BB\Process;

use Carbon\Carbon;

class CheckLeavingUsers
{

    public function run()
    {

        $today = new Carbon();

        //Fetch and check over active users which have a status of leaving
        $users = \User::leaving()->notSpecialCase()->get();
        foreach ($users as $user) {
            if ($user->subscription_expires->lt($today)) {
                //User has passed their expiry date

                //set the status to left and active to false
                $user->leave();

                //an email will be sent by the user observer
            }

        }
    }

} 