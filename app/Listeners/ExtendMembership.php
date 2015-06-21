<?php

namespace BB\Listeners;

use BB\Events\SubscriptionChargePaid;
use BB\Repo\UserRepository;
use Codeception\Events;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExtendMembership
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Create the event listener.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param SubscriptionChargePaid $event
     */
    public function handle(SubscriptionChargePaid $event)
    {
        /** @var $user \BB\Entities\User */
        $user = $this->userRepository->getById($event->subscriptionCharge->user_id);

        $user->extendMembership(null, $event->subscriptionCharge->payment_date->addMonth());
    }
}
