<?php namespace BB\Process;

use BB\Entities\User;
use BB\Helpers\MembershipPayments;
use BB\Repo\UserRepository;
use Carbon\Carbon;

class CheckPaymentWarnings
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function run()
    {

        $today = new Carbon();

        //Fetch and check over active users which have a status of leaving
        $users = User::paymentWarning()->get();
        foreach ($users as $user) {
            /** @var $user \BB\Entities\User */
            if ($user->subscription_expires->lt($today)) {
                //User has passed their expiry date
                echo $user->name . ' has a payment warning and has passed their expiry date'.PHP_EOL;

                //Check the actual expiry date

                //When did their last sub payment expire
                $paidUntil = MembershipPayments::lastUserPaymentExpires($user->id);

                //What grace period do they have - when should we give them to
                $cutOffDate = MembershipPayments::getSubGracePeriodDate($user->payment_method);

                //If the cut of date is greater than (sooner) than the last payment date "expire" them
                if (($paidUntil == false) || $cutOffDate->subDays(2)->gt($paidUntil)) {
                    //set the status to left and active to false
                    $this->userRepository->memberLeft($user->id);
                    echo $user->name . ' marked as having left'.PHP_EOL;
                }

                //an email will be sent by the user observer
            } else {
                echo $user->name . ' has a payment warning but is within their expiry date'.PHP_EOL;
            }
        }
    }

} 