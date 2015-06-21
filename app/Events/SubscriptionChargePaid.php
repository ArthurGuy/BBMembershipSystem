<?php

namespace BB\Events;

use BB\Entities\SubscriptionCharge;
use BB\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubscriptionChargePaid extends Event
{
    use SerializesModels;

    /**
     * @var SubscriptionCharge
     */
    public $subscriptionCharge;

    /**
     * Create a new event instance.
     *
     * @param SubscriptionCharge $subscriptionCharge
     */
    public function __construct(SubscriptionCharge $subscriptionCharge)
    {
        $this->subscriptionCharge = $subscriptionCharge;
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
