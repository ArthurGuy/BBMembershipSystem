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
     * @var bool
     */
    public $delayed;

    /**
     * Create a new event instance.
     *
     * @param KeyFob $keyFob
     * @param string $service
     * @param Carbon $date
     * @param bool   $delayed The old door entry system sends over historical records
     */
    public function __construct(KeyFob $keyFob, $service, Carbon $date = null, $delayed = false)
    {
        $this->keyFob  = $keyFob;
        $this->service = $service;
        $this->date    = $date;
        $this->delayed = $delayed;
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
