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
        $activity = $this->activityRepository->recordMemberActivity($event->keyFob->user->id, $event->keyFob->id, $event->service, $event->date);

        //The old door entry system may send over historical records, make sure these are marked as such
        if ($event->delayed) {
            $activity->delayed = true;
            $activity->save();
        }
    }
}
