<?php

namespace BB\Listeners;

use BB\Events\MemberActivity;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlackActivityNotification
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MemberActivity $event
     */
    public function handle(MemberActivity $event)
    {
        if (\App::environment('production')) {
            // \Slack::send($event->keyFob->user->name . ' is in the space');
        }
    }
}
