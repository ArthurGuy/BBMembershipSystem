<?php namespace BB\Http\Controllers;

class HomeController extends Controller
{


    public function index()
    {
        \View::share('body_class', 'home');
        return view('home');
    }



}
