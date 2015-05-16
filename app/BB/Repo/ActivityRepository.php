<?php namespace BB\Repo;

use BB\Entities\Activity;
use Carbon\Carbon;

class ActivityRepository extends DBRepository
{


    /**
     * @var Activity
     */
    protected $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch all entries for a paticular date
     *
     * @param \DateTime $startDate
     * @return mixed
     */
    public function getForDate(\DateTime $startDate)
    {
        $startDate = $startDate->setTime(0, 0, 0);
        $endDate   = $startDate->copy()->addDay();

        return $this->model->with('user', 'user.profile')->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->where('service', 'main-door')
            ->where('response', '200')
            ->orderBy('created_at', 'desc')
            ->get();
    }


    /**
     * Record an access attempt
     *
     * @param $data
     * @return AccessLog
     */
    public function logAccessAttempt($data)
    {
        return $this->model->create($data);
    }


    public function activeUsersForPeriod(\DateTime $startDate, \DateTime $endDate)
    {
        $startDate = $startDate->setTime(0, 0, 0);
        $endDate   = $endDate->setTime(23, 59, 59);

        return $this->model
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->where('service', 'main-door')
            ->where('response', '200')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Record a generic activity entry for the user
     *
     * @param        $userId
     * @param        $keyFobId
     * @param        $deviceKey
     * @param Carbon $time
     * @return static
     */
    public function recordMemberActivity($userId, $keyFobId, $deviceKey, Carbon $time = null)
    {
        if (empty($time)) {
            $time = Carbon::now();
        }
        return $this->model->create([
            'user_id'    => $userId,
            'key_fob_id' => $keyFobId,
            'service'    => $deviceKey,
            'response'   => 200,
            'created_at' => $time
        ]);
    }


} 