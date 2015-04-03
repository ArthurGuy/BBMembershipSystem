@extends('layouts.main')

@section('page-title')
Tools &amp; Equipment
@stop

@section('meta-title')
Tools and Equipment
@stop

@section('main-tab-bar')

@stop


@section('content')

    <div class="list-group">
        @foreach($equipment as $toolId => $tool)

            <a href="{{ route('equipment.show', $toolId) }}" class="list-group-item">
                {{ $tool->name }}
                @if (!$tool->working)<span class="label label-danger">Out of action</span>@endif
            </a>

        @endforeach
    </div>

@stop
