<?php

namespace BB\Listeners;

use BB\Events\NewMemberNotification;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlackMemberNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewMemberNotification  $event
     * @return void
     */
    public function handle(NewMemberNotification $event)
    {
        if (\App::environment('production')) {

            //If the user doesn't have a slack username registered there is nothing we can do
            if (empty($event->notification->user()->slack_username))
            {
                return;
            }

            // \Slack::to($event->notification->user()->slack_username)->send($event->notification->message);

            $event->notification->update(['notified_method' => 'slack', 'notified_at' => Carbon::now()]);
        }
    }
}
