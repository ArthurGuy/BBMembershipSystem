@extends('layouts.main')

@section('meta-title')
    Devices
@stop

@section('page-title')
    Devices
@stop


@section('content')


<table class="table memberList">
    <thead>
        <tr>
            <th>Device ID</th>
            <th>Queued Commands</th>
            <th>Last Boot</th>
            <th>Last Heartbeat</th>
        </tr>
    </thead>
    <tbody>
        @each('devices.index-row', $devices, 'device')
    </tbody>
</table>

@stop
