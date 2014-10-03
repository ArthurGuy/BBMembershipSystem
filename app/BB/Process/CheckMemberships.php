<?php namespace BB\Process;

use BB\Helpers\MembershipPayments;

/**
 * Loop through each member and look at their last subscription payment
 *   If its over a month ago (plus a grace period) mark them as having a payment warning
 * Class CheckMemberships
 * @package BB\Process
 */
class CheckMemberships {

    public function run()
    {

        $users = \User::active()->where('status', '=', 'active')->notSpecialCase()->get();
        foreach ($users as $user)
        {
            echo $user->name;
            $expired = false;

            $cutOffDate = MembershipPayments::getSubGracePeriodDate($user->paument_method);
            if ($user->subscription_expires->lt($cutOffDate))
            {
                //echo "- Expired";
                $expired = true;
            }

            //Check for payments first
            if ($expired)
            {
                $paidUntil = MembershipPayments::lastUserPaymentExpires($user->id);
                if ($paidUntil)
                {
                    if ($user->subscription_expires->gt($paidUntil))
                    {
                        $user->extendMembership($user->payment_method, $paidUntil);

                        //This may not be true but it simplifies things now and tomorrows process will deal with it
                        $expired = false;
                    }
                }
            }
            if ($expired)
            {
                echo " - Expired";
                $user->status = 'payment-warning';
                $user->save();
            }


            echo '<br />'.PHP_EOL;
        }
    }
} 