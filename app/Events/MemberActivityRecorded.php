<?php

namespace BB\Events;

use BB\Entities\Activity;
use BB\Entities\User;
use BB\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberActivityRecorded extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var User
     */
    protected $user;
    /**
     * @var Activity
     */
    private $activity;

    /**
     * Create a new event instance.
     *
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->user     = User::find($activity->user_id);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['activity'];
    }

    /**
     * @return array
     */
    public function broadcastWith()
    {
        $data = [
            'activity' => [
                'id'         => $this->activity->id,
                'service'    => $this->activity->service,
                'response'   => $this->activity->response,
                'key_fob_id' => $this->activity->key_fob_id,
                'time'       => $this->activity->created_at->toTimeString(),
            ]
        ];
        if ($this->user) {
            $data['user'] = [
                'id'   => $this->user->id,
                'name' => $this->user->name,
                'hash' => $this->user->hash,
            ];
        }

        return $data;
    }
}
