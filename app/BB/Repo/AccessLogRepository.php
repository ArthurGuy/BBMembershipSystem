<?php namespace BB\Repo;

class AccessLogRepository extends DBRepository {


    /**
     * @var AccessLog
     */
    protected $model;

    function __construct(\AccessLog $model)
    {
        $this->model = $model;
    }

    /**
     * Fetch all entries for a paticular date
     * @param \DateTime $startDate
     * @return mixed
     */
    public function getForDate(\DateTime $startDate)
    {
        $startDate = $startDate->setTime(0, 0, 0);
        $endDate = $startDate->copy()->addDay();
        return $this->model->with('user')->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->where('service', 'main-door')
            ->where('response', '200')
            ->orderBy('created_at', 'desc')
            ->get();
    }


    /**
     * Record an access attempt
     * @param $data
     * @return mixed
     */
    public function logAccessAttempt($data)
    {
        return $this->model->create($data);
    }


} 