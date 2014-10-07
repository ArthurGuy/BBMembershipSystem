@extends('layouts.main')

@section('title')
Build Brighton Members
@stop

@section('content')
<div class="page-header">
    <h1>Members</h1>
</div>

<div class="member-grid">
    <div class="row">
        @foreach ($users as $user)
        <div class="col-sm-6 col-md-4 col-lg-2">
            <div class="thumbnail">
                @if ($user->profile_photo)
                    @if ($user->profile_photo_private)
                        <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="100" height="100" />
                    @else
                        <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="100" height="100" />
                    @endif
                @else
                    <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="100" height="100" />
                @endif
                <div class="caption">
                    <strong><a href="{{ route('members.show', $user->id) }}">{{ $user->name }}</a></strong>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop