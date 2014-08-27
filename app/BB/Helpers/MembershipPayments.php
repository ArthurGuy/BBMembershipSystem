<?php namespace BB\Helpers;

class MembershipPayments
{


    /**
     * Fetch the date of the users last subscription payment
     * @param $userId
     * @return bool|\DateTime
     */
    public static function lastUserPaymentDate($userId)
    {
        $latestSubPayment = \Payment::where('user_id', $userId)->where('reason', 'subscription')->orderBy(
            'created_at',
            'desc'
        )->first();
        if ($latestSubPayment) {
            return $latestSubPayment->created_at;
        }
        return false;
    }

    public static function lastUserPaymentExpires($userId)
    {
        $date = self::lastUserPaymentDate($userId);
        if ($date) {
            return $date->addMonth();
        }
        return false;
    }
} 