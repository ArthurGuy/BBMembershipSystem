<?php namespace BB\Mailer;

use BB\Entities\User;

class UserMailer {


    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Send a welcome email
     */
    public function sendWelcomeMessage()
    {
        $user = $this->user;
        \Mail::queue('emails.welcome', ['user'=>$user], function($message) use ($user)
        {
            $message->to($user->email, $user->name)->subject('Welcome to Build Brighton!');
        });
    }



    public function sendPaymentWarningMessage()
    {
        $user = $this->user;
        \Mail::queue('emails.payment-warning', ['user'=>$user], function($message) use ($user)
        {
            $message->to($user->email, $user->email)->subject('We have detected a payment problem');
        });
    }


    public function sendLeftMessage()
    {
        $user = $this->user;

        $storageBoxRepository = \App::make('BB\Repo\StorageBoxRepository');

        $memberBox = $storageBoxRepository->getMemberBox($this->user->id);

        \Mail::queue('emails.user-left', ['user'=>$user, 'memberBox'=>$memberBox], function($message) use ($user)
        {
            $message->to($user->email, $user->email)->subject('Sorry to see you go');
        });
    }


    public function sendNotificationEmail($subject, $message)
    {
        $user = $this->user;
        \Mail::queue('emails.notification', ['messageBody'=>$message, 'user'=>$user], function($message) use ($user, $subject)
        {
            $message->addReplyTo('trustees@buildbrighton.com', 'Build Brighton Trustees');
            $message->to($user->email, $user->email)->subject($subject);
        });
    }

    public function sendSuspendedMessage()
    {
        $user = $this->user;
        \Mail::queue('emails.suspended', ['user'=>$user], function($message) use ($user)
        {
            $message->addReplyTo('trustees@buildbrighton.com', 'Build Brighton Trustees');
            $message->to($user->email, $user->email)->subject('Your Build Brighton membership has been suspended');
        });
    }

} 