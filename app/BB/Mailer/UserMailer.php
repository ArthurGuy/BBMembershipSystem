<?php namespace BB\Mailer;

class UserMailer {


    /**
     * @var User
     */
    private $user;

    public function __construct(\User $user)
    {
        $this->user = $user;
    }


    /**
     * Send a welcome email
     */
    public function sendWelcomeMessage()
    {
        \Mail::send('emails.welcome', ['user'=>$this->user], function($message)
        {
            //$message->to($this->user->email, $this->user->name)->subject('Welcome to Build Brighton!');
            $message->to('arthur@assys.net', $this->user->name)->subject('Welcome to Build Brighton!');
        });
    }

} 