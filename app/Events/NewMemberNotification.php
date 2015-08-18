<?php

namespace BB\Events;

use BB\Entities\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMemberNotification extends Event
{
    use SerializesModels;
    /**
     * @var Notification
     */
    public $notification;

    /**
     * Create a new event instance.
     *
     * @param Notification $notification
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
