<?php

namespace BB\Listeners;

use BB\Events\MemberActivity;
use BB\Repo\ActivityRepository;use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordMemberActivity
{
    /**
     * @var ActivityRepository
     */
    private $activityRepository;

    /**
     * Create the event listener.
     *
     * @param ActivityRepository $activityRepository
     */
    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Handle the event.
     *
     * @param MemberActivity $event
     */
    public function handle(MemberActivity $event)
    {
        $this->activityRepository->recordMemberActivity($event->keyFob->user->id, $event->keyFob->id, $event->service);
    }
}
