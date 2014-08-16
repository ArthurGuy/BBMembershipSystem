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
        if (($original['status'] == 'setting-up') && ($user->status != 'setting-up'))
        {
            //$this->newUser($user);
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
} 