@extends('layouts.main')

@section('meta-title')
    Build Brighton Groups
@stop
@section('page-title')
    Build Brighton Groups
@stop

@section('content')

    <div class="row">
        <div class="list-group">
            @foreach($roles as $role)
                <a href="{{ route('group-listing', $role->name) }}" class="list-group-item">
                    <h4 class="list-group-item-heading">{{ $role->title }}</h4>
                    <p class="list-group-item-text">{{ $role->description }}</p>
                </a>
            @endforeach
        </div>
    </div>

@stop