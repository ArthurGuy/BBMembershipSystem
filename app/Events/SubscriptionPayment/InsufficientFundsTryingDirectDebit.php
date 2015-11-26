<?php

namespace BB\Events\SubscriptionPayment;

use BB\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InsufficientFundsTryingDirectDebit extends Event
{
    use SerializesModels;

    /**
     * @var
     */
    public $userId;

    /**
     * @var
     */
    public $ubChargeId;

    /**
     * @param $userId
     * @param $ubChargeId
     */
    public function __construct($userId, $ubChargeId)
    {
        $this->userId = $userId;
        $this->ubChargeId = $ubChargeId;
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
