<?php

namespace BB\Listeners;

use BB\Events\ExpenseWasApproved;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailMemberAboutApprovedExpense
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
     * @param  ExpenseWasApproved  $event
     * @return void
     */
    public function handle(ExpenseWasApproved $event)
    {
        $user = $event->expense->user;
        $this->mailer->send('emails.expense-approved', ['user' => $event->expense->user, 'expense' => $event->expense], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Your expense was approved');
        });
    }
}
