<?php namespace BB\Helpers;

use Carbon\Carbon;

class MembershipPayments
{


    /**
     * Fetch the date of the users last subscription payment
     *
     * @param $userId
     * @return false|Carbon
     */
    public static function lastUserPaymentDate($userId)
    {
        $subscriptionChargeRepository = \App::make('BB\Repo\SubscriptionChargeRepository');
        /** @var $subscriptionChargeRepository \BB\Repo\SubscriptionChargeRepository */
        $latestCharge = $subscriptionChargeRepository->getMembersLatestPaidCharge($userId);
        if ($latestCharge) {
            return $latestCharge->charge_date;
        }
        return false;
    }

    /**
     * Fetch the expiry date based on the users last sub payment
     *
     * @param $userId
     * @return false|Carbon
     */
    public static function lastUserPaymentExpires($userId)
    {
        $date = self::lastUserPaymentDate($userId);
        if ($date) {
            return $date->setTime(0, 0, 0)->addMonth();
        }

        return false;
    }

    /**
     * Get the date the users sub payment should be valid to
     *   This handles the different grace periods for the different payment methods.
     *
     * @param string $paymentMethod
     * @param Carbon $refDate Defaults to today as the ref point, this can be overridden
     * @return Carbon
     */
    public static function getSubGracePeriodDate($paymentMethod, Carbon $refDate = null)
    {
        if (is_null($refDate)) {
            $refDate = Carbon::now();
        }

        //The time needs to be zeroed so that comparisons with pure dates work
        $refDate->setTime(0, 0, 0);

        $standingOrderCutoff      = $refDate->copy()->subMonth()->subDays(7);
        $paypalCutoff             = $refDate->copy()->subDays(7);
        $goCardlessCutoff         = $refDate->copy()->subDays(14);
        $goCardlessVariableCutoff = $refDate->copy()->subDays(10);
        $otherCutoff              = $refDate->copy()->subDays(7);

        if ($paymentMethod == 'gocardless') {
            return $goCardlessCutoff;
        } elseif ($paymentMethod == 'gocardless-variable') {
            return $goCardlessVariableCutoff;
        } elseif ($paymentMethod == 'paypal') {
            return $paypalCutoff;
        } elseif ($paymentMethod == 'standing-order') {
            return $standingOrderCutoff;
        } else {
            return $otherCutoff;
        }
    }
} 