<?php namespace BB\Observer;

use BB\Mailer\UserMailer;

class UserObserver {

    /**
     * Look at the user record each time its saved and fire events
     * @param $user
     */
    public function saved($user)
    {
        $original = $user->getOriginal();

        //Use status changed from setting-up to something else
        if (($original['status'] == 'setting-up') && ($user->status != 'setting-up'))
        {
            $this->newUser($user);
        }

        //User status changed to payment warning
        if (($original['status'] != 'payment-warning') && ($user->status == 'payment-warning'))
        {
            $this->paymentWarning($user);
        }
    }

    /**
     * Method called when a user is activated
     * @param $user
     */
    private function newUser($user)
    {
        $userMailer = new UserMailer($user);
        $userMailer->sendWelcomeMessage();
    }


    private function paymentWarning($user)
    {
        $userMailer = new UserMailer($user);
        $userMailer->sendPaymentWarningMessage();
    }
} 