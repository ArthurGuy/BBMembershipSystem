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
                <h3>{{ $room->getName() }}</h3>
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
            @foreach($requiresInduction as $tool)
                <tr>
                    <td>
                        <a href="{{ route('equipment.show', $tool->key) }}">{{ $tool->name }}</a>
                    </td>
                    <td>{!! $tool->present()->accessFee() !!}</td>
                    <td>{!! $tool->present()->usageCost() !!}</td>
                    <td>
                        @if (!$tool->isWorking())<span class="label label-danger">Out of action</span>@endif
                        @if ($tool->isPermaloan())<span class="label label-warning">Permaloan</span>@endif
                    </td>
                    <td>
                        @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
                            <span class="pull-right"><a href="{{ route('equipment.edit', $tool->key) }}" class="btn-sm">Edit</a></span>
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
        @foreach($doesntRequireInduction as $tool)
            <tr>
                <td>
                    <a href="{{ route('equipment.show', $tool->key) }}">{{ $tool->name }}</a>
                </td>
                <td>{!! $tool->present()->usageCost() !!}</td>
                <td>
                    @if (!$tool->working)<span class="label label-danger">Out of action</span>@endif
                    @if ($tool->isPermaloan())<span class="label label-warning">Permaloan</span>@endif
                </td>
                <td>
                    @if (!Auth::guest() && Auth::user()->hasRole('equipment'))
                        <span class="pull-right"><a href="{{ route('equipment.edit', $tool->key) }}" class="btn-sm">Edit</a></span>
                    @endif
                </td>
            </tr>
        @endforeach
        </table>
    </div>

    For changes to the information on the equipment pages please contact someone on
    the <a href="{{ route('group-listing', 'equipment') }}">equipment</a> team

@stop
