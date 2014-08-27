<?php namespace BB\Process;

use BB\Helpers\MembershipPayments;
use Carbon\Carbon;

/**
 * Loop through each member and look at their last subscription payment
 *   If its over a month ago (plus a grace period) mark them as having a payment warning
 * Class CheckMemberships
 * @package BB\Process
 */
class CheckMemberships {

    public function run()
    {

        $today = new Carbon();
        $standingOrderCutoff = $today->subMonth()->subDays(7);
        $paypalCutoff = $today->subDays(7);
        $otherCutoff = $today->subDays(7);

        $users = \User::active()->where('status', '=', 'active')->notSpecialCase()->get();
        foreach ($users as $user)
        {
            echo $user->name;
            $expired = false;

            if ($user->payment_method == 'gocardless')
            {

            }
            elseif ($user->payment_method == 'standing-order')
            {
                if ($user->subscription_expires->lt($standingOrderCutoff))
                {
                    echo "- S/O Expired";
                    $expired = true;
                }
            }
            elseif ($user->payment_method == 'paypal')
            {
                if ($user->subscription_expires->lt($paypalCutoff))
                {
                    echo "- Paypal Expired";
                    $expired = true;
                }
            }
            else
            {
                if ($user->subscription_expires->lt($otherCutoff))
                {
                    echo "- Other Expired";
                    $expired = true;
                }
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