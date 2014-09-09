<?php

class ActivityController extends \BaseController {

    protected $layout = 'layouts.main';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $date = Input::get('date', \Carbon\Carbon::now()->format('Y-m-d'));
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $date);
        $today = \Carbon\Carbon::now()->setTime(0, 0, 0);
        $startDate = $date->setTime(0, 0, 0);
        $endDate = $startDate->copy()->addDay();
        $logEntries = AccessLog::where('created_at', '>', $startDate)->where('created_at', '<', $endDate)->where('service', 'main-door')->where('response', '200')->orderBy('created_at', 'desc')->get();

        $nextDate = null;
        if ($startDate->lt($today)) {
            $nextDate = $startDate->copy()->addDay();
        }
        $previousDate = $startDate->copy()->subDay();

        $this->layout->content = View::make('activity.index')->with('logEntries', $logEntries)->with('date', $startDate)->with('nextDate', $nextDate)->with('previousDate', $previousDate);
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
