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
        <div class="col-sm-12 col-md-9 col-lg-9">

            <p>
                The various groups within Build Brighton are a way to give people the authority to take charge, make decisions and get things done.<br />
                Trying to obtain a consensus from a wide user base can be difficult so by bringing the members who are
                interested in a particular area together they can make decisions amongst themselves and get things done.
            </p>
            <p>
                The way the groups will run is still very open and fluid but as always if you have any suggestions please get in contact.<br />
                Anyone can join the main Build Brighton groups, all you need to do is let one of the trustees know.<br />
            </p>

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
    </div>

@stop