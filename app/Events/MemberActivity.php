<?php

namespace BB\Events;

use BB\Entities\KeyFob;
use BB\Events\Event;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberActivity extends Event
{
    use SerializesModels;
    /**
     * @var KeyFob
     */
    public $keyFob;
    /**
     * @var string
     */
    public $service;
    /**
     * @var Carbon
     */
    public $date;

    /**
     * Create a new event instance.
     *
     * @param KeyFob $keyFob
     * @param string $service
     * @param Carbon $date
     */
    public function __construct(KeyFob $keyFob, $service, Carbon $date = null)
    {
        $this->keyFob = $keyFob;
        $this->service = $service;
        $this->date = $date;
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
