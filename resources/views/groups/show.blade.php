@extends('layouts.main')

@section('meta-title')
    Group: {{ $role->title }}
@stop
@section('page-title')
    Group: {{ $role->title }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <p class="lead">
                {{ $role->description }}
            </p>
            @if ($role->email_public)
            Email: <a href="{{ $role->email_public }}">{{ $role->email_public }}</a>
            @endif

            <h4>Group members</h4>
            <div class="list-group">
                @foreach($role->users as $user)
                    <a href="{{ route('members.show', $user->id) }}" class="list-group-item">
                        {!! HTML::memberPhoto($user->profile, $user->hash, 50, '') !!}
                        {{ $user->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@stop