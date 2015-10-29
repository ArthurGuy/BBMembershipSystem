<?php

namespace BB\Events;

use BB\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberBalanceChanged extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $userId;

    /**
     * Create a new event instance.
     *
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
