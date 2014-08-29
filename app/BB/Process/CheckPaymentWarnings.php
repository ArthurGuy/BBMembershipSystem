<?php namespace BB\Process;

use BB\Helpers\MembershipPayments;
use Carbon\Carbon;

class CheckPaymentWarnings
{

    public function run()
    {

        $today = new Carbon();

        //Fetch and check over active users which have a status of leaving
        $users = \User::paymentWarning()->notSpecialCase()->get();
        foreach ($users as $user) {
            if ($user->subscription_expires->lt($today)) {
                //User has passed their expiry date

                //Check the actual expiry date

                //When did their last sub payment expire
                $paidUntil = MembershipPayments::lastUserPaymentExpires($user->id);

                //What grace period do they have - when should we give them to
                $cutOffDate = MembershipPayments::getSubGracePeriodDate($user->paument_method);

                if ($cutOffDate->addDays(2)->lt($paidUntil))
                {
                    //set the status to left and active to false
                    $user->leave();
                }

                //an email will be sent by the user observer
            }
        }
    }

} 