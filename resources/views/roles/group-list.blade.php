@extends('layouts.main')

@section('meta-title')
    Groups
@stop
@section('page-title')
    Groups
@stop

@section('content')

    <div class="row">
        <div class="list-group">
            @foreach($roles as $role)
                <a href="{{ route('group-listing', $role->name) }}" class="list-group-item">
                    {{ $role->title }} - {{ $role->description }}
                </a>
            @endforeach
        </div>
    </div>

@stop