<?php

class ActivityController extends \BaseController {

    protected $layout = 'layouts.main';
    /**
     * @var
     */
    private $accessLogRepository;

    function __construct(\BB\Repo\AccessLogRepository $accessLogRepository)
    {
        $this->accessLogRepository = $accessLogRepository;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $date = Input::get('date', \Carbon\Carbon::now()->format('Y-m-d'));
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->setTime(0, 0, 0);
        $today = \Carbon\Carbon::now()->setTime(0, 0, 0);

        $logEntries = $this->accessLogRepository->getForDate($date);

        $nextDate = null;
        if ($date->lt($today)) {
            $nextDate = $date->copy()->addDay();
        }
        $previousDate = $date->copy()->subDay();

        $this->layout->content = View::make('activity.index')->with('logEntries', $logEntries)->with('date', $date)->with('nextDate', $nextDate)->with('previousDate', $previousDate);
	}


	/**
	 * @return Response
	 */
	public function realtime()
	{
        $this->layout->content = View::make('activity.realtime');
	}



}
