<?php

namespace BB\Listeners;

use BB\Events\NewExpenseSubmitted;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTrusteesAboutExpense
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
     * @param  NewExpenseSubmitted  $event
     * @return void
     */
    public function handle(NewExpenseSubmitted $event)
    {
        $this->mailer->send('emails.new-expense', ['user' => $event->expense->user, 'expense' => $event->expense], function ($m) {
            $m->to('trustees@buildbrighton.com', 'Trustees')->subject('New expense submitted');
        });
    }
}
