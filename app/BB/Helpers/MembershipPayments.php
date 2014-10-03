<?php namespace BB\Helpers;

use Carbon\Carbon;

class MembershipPayments
{


    /**
     * Fetch the date of the users last subscription payment
     * @param $userId
     * @return bool|Carbon
     */
    public static function lastUserPaymentDate($userId)
    {
        $latestSubPayment = \Payment::where('user_id', $userId)
            ->where('reason', 'subscription')
            ->where('status', 'paid')
            ->orWhere('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($latestSubPayment) {
            return $latestSubPayment->created_at;
        }
        return false;
    }

    /**
     * Fetch the expiry date based on the users last sub payment
     * @param $userId
     * @return bool|Carbon
     */
    public static function lastUserPaymentExpires($userId)
    {
        $date = self::lastUserPaymentDate($userId);
        if ($date) {
            return $date->addMonth();
        }
        return false;
    }

    /**
     * Get the date the users sub payment should be valid to
     *   This handles the different grace periods for the different payment methods.
     * @param string           $paymentMethod
     * @param Carbon $refDate Defaults to today as the ref point, this can be overridden
     * @return Carbon
     */
    public static function getSubGracePeriodDate($paymentMethod, Carbon $refDate = null)
    {
        if (is_null($refDate)) {
            $refDate = new Carbon();
        }
        $standingOrderCutoff = $refDate->subMonth()->subDays(7);
        $paypalCutoff        = $refDate->subDays(7);
        $goCardlessCutoff    = $refDate->subDays(7);
        $otherCutoff         = $refDate->subDays(7);

        if ($paymentMethod == 'gocardless') {
            return $goCardlessCutoff;
        } elseif ($paymentMethod == 'paypal') {
            return $paypalCutoff;
        } elseif ($paymentMethod == 'standing-order') {
            return $standingOrderCutoff;
        } else {
            return $otherCutoff;
        }
    }
} 