<?php

class HomeController extends BaseController {


    protected $layout = 'layouts.main';


	public function index()
	{
        View::share('body_class', 'home');
        $this->layout->content = View::make('home');
	}



}
