<?php

namespace BB\Http\Controllers\ACS;

use Illuminate\Http\Request;
use BB\Http\Requests;
use BB\Http\Controllers\Controller;

/**
 * @SWG\Info(title="Build Brighton API", version="1")
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="api_key"
 * )
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
     *     tags={"acs"},
     *     description="Returns an OK message, useful for verifying the access token works",
     *     @SWG\Response(response="200", description="OK")
     * )
     */
    public function index()
    {
        return ['OK'];
    }
}
