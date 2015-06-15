@extends('layouts.main')

@section('meta-title')
    Detected Devices
@stop

@section('page-title')
    Detected Devices
@stop


@section('content')


<table class="table memberList">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Mac Address</th>
            <th>Display Name</th>
            <th>Occurrences</th>
        </tr>
    </thead>
    <tbody>
        @each('detected_devices.index-row', $devices, 'device')
    </tbody>
</table>

@stop
