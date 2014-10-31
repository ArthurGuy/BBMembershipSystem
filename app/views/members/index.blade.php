@extends('layouts.main')

@section('meta-title')
Build Brighton Members
@stop

@section('page-title')
Members
@stop


@section('content')

<div class="memberGrid">
    <div class="row">
        @foreach ($users as $user)
        <div class="col-sm-6 col-md-4 col-lg-2">
            <div class="memberBlock">
                <a href="{{ route('members.show', $user->id) }}">
                @if ($user->profile->profile_photo)
                    @if (Auth::guest() && $user->profile->profile_photo_private)
                        <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="200" height="200" class="profilePhoto" />
                    @else
                        <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="200" height="200" class="profilePhoto" />
                    @endif
                @else
                    <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="200" height="200" class="profilePhoto" />
                @endif
                <div class="memberDetails">
                    <strong>{{ $user->name }}</strong>
                </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>

@stop