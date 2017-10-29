@extends('layouts.main')

@section('meta-title')
    Devices
@stop

@section('page-title')
    Devices
@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->hasRole('acs'))
        <a class="btn btn-secondary" href="{{ route('devices.create') }}">Setup a new device</a>
    @endif
@stop

@section('content')


<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Device ID</th>
            <th>API Key</th>
            <th>Entry Device</th>
            <th>Last Heartbeat</th>
            <th>Last Boot</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @each('devices.index-row', $devices, 'device')
    </tbody>
</table>

    <p>
        <a href="/api-docs">API Docs</a>
    </p>

@stop
