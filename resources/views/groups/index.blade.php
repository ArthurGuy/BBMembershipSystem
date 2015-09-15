@extends('layouts.main')

@section('meta-title')
    Build Brighton Groups
@stop

@section('page-title')
    Build Brighton Groups
@stop

@section('page-action-buttons')
    @if (!Auth::guest() && Auth::user()->hasRole('admin'))
    <a class="btn btn-secondary" href="{{ route('roles.index') }}">Edit Groups</a>
    @endif
@stop

@section('content')

    <div class="row">
        <div class="list-group">
            @foreach($roles as $role)
                <a href="{{ route('groups.show', $role->name) }}" class="list-group-item">
                    <h4 class="list-group-item-heading">{{ $role->title }}</h4>
                    <p class="list-group-item-text">
                        {{ $role->description }}<br />
                        <em>{{ $role->users->count() }} Members</em>
                    </p>
                </a>
            @endforeach
        </div>
    </div>

@stop