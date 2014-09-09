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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
