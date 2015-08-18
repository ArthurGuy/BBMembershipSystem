<?php

namespace BB\Http\Controllers;

use BB\Entities\Notification;
use Illuminate\Contracts\Validation\UnauthorizedException;
use Illuminate\Http\Request;
use Auth;
use BB\Http\Requests;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->get();

        return view('notifications.index', ['notifications' => $notifications]);
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
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'unread' => 'boolean'
        ]);

        $notification = Notification::findOrFail($id);

        if ($notification->user_id !== Auth::id()) {
            throw new UnauthorizedException();
        }

        $notification->update($request->only('unread'));

        return $notification;
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
