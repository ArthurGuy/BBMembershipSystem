<?php

namespace BB\Http\Controllers\ACS;

use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;

/**
 * @SWG\Info(title="ACS API", version="1")
 */
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @SWG\Get(
     *     path="/acs/test",
     *     @SWG\Response(response="200", description="An example resource")
     * )
     */
    public function index()
    {
        return ['OK'];
    }
}
