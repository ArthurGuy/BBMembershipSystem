<?php namespace BB\Process;

use Carbon\Carbon;

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
                $latestSubPayment = $user->payments()->where('reason', 'subscription')->orderBy('created_at', 'desc')->first();
                if ($latestSubPayment)
                {
                    $paidUntil = $latestSubPayment->created_at->addMonth();
                    if ($user->subscription_expires->lt($paidUntil))
                    {
                        $user->extendMembership($user->payment_method, $paidUntil);

                        //This may not be true but it simplifies things now and tomorrows process will deal with it
                        $expired = false;
                    }
                }
            }
            if ($expired)
            {
                $user->status = 'payment-warning';
                //$user->save();
            }


            echo '<br />'.PHP_EOL;
        }
    }
} 