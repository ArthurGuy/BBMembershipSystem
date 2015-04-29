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

    <h3>Equipment requiring an induction</h3>
    <div class="list-group">
        @foreach($requiresInduction as $tool)

            <a href="{{ route('equipment.show', $tool->key) }}" class="list-group-item">
                {{ $tool->name }}
                @if (!$tool->working)<span class="label label-danger">Out of action</span>@endif
            </a>

        @endforeach
    </div>

    <h3>Equipment ready to use</h3>
    <div class="list-group">
        @foreach($doesntRequireInduction as $tool)

            <a href="{{ route('equipment.show', $tool->key) }}" class="list-group-item">
                {{ $tool->name }}
                @if (!$tool->working)<span class="label label-danger">Out of action</span>@endif
            </a>

        @endforeach
    </div>

@stop
