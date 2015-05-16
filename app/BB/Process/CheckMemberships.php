<?php namespace BB\Process;

use BB\Entities\User;
use BB\Helpers\MembershipPayments;
use BB\Services\MemberSubscriptionCharges;

/**
 * Loop through each member and look at their last subscription payment
 *   If its over a month ago (plus a grace period) mark them as having a payment warning
 * Class CheckMemberships
 * @package BB\Process
 */
class CheckMemberships
{

    /**
     * @var MemberSubscriptionCharges
     */
    private $memberSubscriptionCharges;

    public function __construct(MemberSubscriptionCharges $memberSubscriptionCharges)
    {
        $this->memberSubscriptionCharges = $memberSubscriptionCharges;
    }

    public function run()
    {

        $users = User::active()->where('status', '=', 'active')->notSpecialCase()->get();
        foreach ($users as $user) {
            /** @var $user \BB\Entities\User */
            echo $user->name;
            $expired = false;

            $cutOffDate = MembershipPayments::getSubGracePeriodDate($user->payment_method);
            if (!$user->subscription_expires || $user->subscription_expires->lt($cutOffDate)) {
                $expired = true;
            }

            //Check for payments first
            if ($expired) {
                $paidUntil = MembershipPayments::lastUserPaymentExpires($user->id);
                //$paidUntil = $this->memberSubscriptionCharges->lastUserChargeExpires($user->id);
                if ($paidUntil) {
                    if ($user->subscription_expires && $user->subscription_expires->lt($paidUntil)) {
                        $user->extendMembership($user->payment_method, $paidUntil);

                        //This may not be true but it simplifies things now and tomorrows process will deal with it
                        $expired = false;
                    }
                }
            }
            if ($expired) {
                $user->setSuspended();
                echo ' - Suspended';
            }


            echo PHP_EOL;
        }
    }
} 