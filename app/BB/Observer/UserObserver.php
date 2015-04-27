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

        //User status changed to payment warning
        if (($original['status'] != 'suspended') && ($user->status == 'suspended'))
        {
            $this->suspended($user);
        }

        //User left
        if (($original['status'] != 'left') && ($user->status == 'left'))
        {
            $this->userLeft($user);
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

        if (\App::environment('production')) {
            \Slack::to("#general")->send($user->name . ' has just joined Build Brighton');
        }
    }

    private function paymentWarning($user)
    {
        $userMailer = new UserMailer($user);
        $userMailer->sendPaymentWarningMessage();
    }

    private function suspended($user)
    {
        $userMailer = new UserMailer($user);
        $userMailer->sendSuspendedMessage();

        if (\App::environment('production')) {
            \Slack::to("#trustees")->send($user->name . ' has been suspended for non payment');
        }
    }

    private function userLeft($user)
    {
        $userMailer = new UserMailer($user);
        $userMailer->sendLeftMessage();

        if (\App::environment('production')) {
            \Slack::to("#trustees")->send($user->name . ' has left Build Brighton');
        }
    }
} 