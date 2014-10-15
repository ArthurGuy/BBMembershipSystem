<?php

class HomeController extends BaseController {


	public function index()
	{
        View::share('body_class', 'home');
        return View::make('home');
	}



}
