@extends('layouts.main')

@section('page-title')
Tools &amp; Equipment
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('main-tab-bar')

@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
        <a class="btn btn-secondary" href="{{ route('equipment.create') }}">Record a new item</a>
    @endif
@stop


@section('content')

    <div class="row">
    @foreach($rooms as $room)
        <div class="col-lg-4">
            <div class="well">
                <h3>{{ $room->name() }}</h3>
            </div>
        </div>
    @endforeach
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Equipment requiring an induction</h3>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Access Fee</th>
                <th>Usage Cost</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            @foreach($requiresInduction as $device)
                <tr>
                    <td>
                        <a href="{{ route('equipment.show', $device->slug()) }}">{{ $device->name() }}</a>
                    </td>
                    <td>{!! $device->cost()->accessFee() !!}</td>
                    <td>{!! $device->cost()->usageCost() !!}</td>
                    <td>
                        @if (!$device->working())<span class="label label-danger">Out of action</span>@endif
                        @if ($device->owner()->permaloan())<span class="label label-warning">Permaloan</span>@endif
                    </td>
                    <td>
                        @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
                            <span class="pull-right"><a href="{{ route('equipment.edit', $device->slug()) }}" class="btn-sm">Edit</a></span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Equipment ready to use</h3>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Usage Cost</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
        @foreach($doesntRequireInduction as $device)
            <tr>
                <td>
                    <a href="{{ route('equipment.show', $device->slug()) }}">{{ $device->name() }}</a>
                </td>
                <td>{!! $device->cost()->usageCost() !!}</td>
                <td>
                    @if (!$device->working())<span class="label label-danger">Out of action</span>@endif
                    @if ($device->owner()->permaloan())<span class="label label-warning">Permaloan</span>@endif
                </td>
                <td>
                    @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
                        <span class="pull-right"><a href="{{ route('equipment.edit', $device->slug()) }}" class="btn-sm">Edit</a></span>
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
    </div>

    For changes to the information on the equipment pages please contact someone on
    the <a href="{{ route('groups.show', 'equipment') }}">equipment</a> team

@stop
