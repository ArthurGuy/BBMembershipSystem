<?php

namespace BB\Listeners;

use BB\Events\ExpenseWasDeclined;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailMemberAboutDeclinedExpense implements ShouldQueue
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
     * @param  ExpenseWasDeclined  $event
     * @return void
     */
    public function handle(ExpenseWasDeclined $event)
    {
        $user = $event->expense->user;
        $this->mailer->send('emails.expense-declined', ['user' => $event->expense->user, 'expense' => $event->expense], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Your expense was declined');
        });
    }
}
