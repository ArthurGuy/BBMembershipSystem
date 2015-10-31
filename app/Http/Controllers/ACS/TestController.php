<?php

namespace BB\Http\Controllers\ACS;

use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ['OK'];
    }
}
