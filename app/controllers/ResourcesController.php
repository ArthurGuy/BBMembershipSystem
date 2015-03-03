<?php

class ResourcesController extends \BaseController {


    function __construct()
    {
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('resources.index');
	}

}
