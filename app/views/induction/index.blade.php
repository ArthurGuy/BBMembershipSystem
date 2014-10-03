@extends('layouts.main')

@section('content')

<div class="page-header">
    <h1>Equipment Training</h1>
</div>

<div class="row">
@foreach($equipment as $toolId => $tool)
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $tool->name }}</h3>
            </div>
            <div class="panel-body">
                <h4>Trainers</h4>
                <div class="member-grid">
                <div class="row">
                @if(isset($trainers[$toolId]))
                    @foreach($trainers[$toolId] as $trainer)
                        <div class="col-sm-4 col-md-2 col-lg-1">
                            <div class="thumbnail">
                                @if ($trainer->profile_photo)
                                    <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($trainer->hash) }}" width="50" height="50" />
                                @else
                                    <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="50" height="50" />
                                @endif
                                <div class="caption">
                                    <strong><a href="{{ route('members.show', $trainer->id) }}">{{ $trainer->name }}</a></strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
                </div>
                </div>
                @if(isset($usersPendingInduction[$toolId]))
                    <h4>Members waiting for an Induction</h4>
                    @foreach($usersPendingInduction[$toolId] as $user)
                        <a href="{{ route('members.show', $user->id) }}">
                            @if ($user->profile_photo)
                                <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="25" height="25" />
                            @else
                                <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="25" height="25" />
                            @endif
                            {{ $user->name }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endforeach
</div>

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