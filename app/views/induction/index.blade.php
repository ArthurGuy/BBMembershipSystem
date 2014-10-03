@extends('layouts.main')

@section('content')

<div class="page-header">
    <h1>Equipment Training</h1>
</div>

@foreach($equipment as $toolId => $tool)
    <div class="">
        <h3>{{ $tool->name }}</h3>
        <h4>Trainers</h4>
        @if(isset($trainers[$toolId]))
            @foreach($trainers[$toolId] as $trainer)
                <a href="{{ route('members.show', $trainer->id) }}">
                    @if ($trainer->profile_photo)
                        <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($trainer->hash) }}" width="40" height="40" class="img-circle" />
                    @else
                        <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="40" height="40" class="img-circle" />
                    @endif
                    {{ $trainer->name }}
                </a>
            @endforeach
        @endif
        @if(isset($usersPendingInduction[$toolId]))
            <h4>Members waiting for an Induction</h4>
            @foreach($usersPendingInduction[$toolId] as $user)
                <a href="{{ route('members.show', $user->id) }}">
                    @if ($user->profile_photo)
                        <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="40" height="40" class="img-circle" />
                    @else
                        <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="40" height="40" class="img-circle" />
                    @endif
                    {{ $user->name }}
                </a>
            @endforeach
        @endif
    </div>
@endforeach


<table class="table">
    <thead>
        <tr>
            <th>Equipment</th>
            <th>Member</th>
            <th>Paid</th>
            <th>Trained</th>
            <th>Trainer</th>
            <th>Trained By</th>
        </tr>
    </thead>
@foreach ($inductions as $induction)
    <tbody>
        <tr>
            <td>{{ $induction->key }}</td>
            <td>{{ $induction->user->name or 'Unknown' }}</td>
            <td>
                @if($induction->paid)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>
                @if($induction->is_trained)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>
                @if($induction->is_trainer)
                <span class="glyphicon glyphicon-ok"></span>
                @endif
            </td>
            <td>{{ $induction->trainer_user->name or '' }}</td>
        </tr>
    </tbody>
@endforeach
</table>

@stop