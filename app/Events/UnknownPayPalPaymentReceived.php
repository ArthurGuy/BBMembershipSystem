<?php

namespace BB\Events;

use BB\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UnknownPayPalPaymentReceived extends Event
{
    use SerializesModels;

    /**
     * @var int
     */
    public $paymentId;
    /**
     * @var string
     */
    public $emailAddress;

    /**
     * Create a new event instance.
     *
     * @param int $paymentId
     * @param     $emailAddress
     */
    public function __construct($paymentId, $emailAddress)
    {
        $this->paymentId = $paymentId;
        $this->emailAddress = $emailAddress;
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
