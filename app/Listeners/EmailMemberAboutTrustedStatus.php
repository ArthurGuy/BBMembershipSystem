<?php

namespace BB\Listeners;

use BB\Events\MemberGivenTrustedStatus;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailMemberAboutTrustedStatus implements ShouldQueue
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * Create the event listener.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param MemberGivenTrustedStatus $event
     */
    public function handle(MemberGivenTrustedStatus $event)
    {
        $user = $event->user;
        $this->mailer->send('emails.made-trusted-member', ['user' => $event->user], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('You have been made a trusted member');
        });
    }
}
