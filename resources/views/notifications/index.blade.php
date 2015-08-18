@extends('layouts.main')

@section('meta-title')
    Notifications
@stop

@section('page-title')
    Notifications
@stop


@section('content')


<table class="table memberList">
    <thead>
        <tr>
            <th>Message</th>
            <th>Type</th>
            <th>Date</th>
            <th>Read</th>
        </tr>
    </thead>
    <tbody>
        @each('notifications.index-row', $notifications, 'notification')
    </tbody>
</table>

@stop
