@extends('layouts.main')

@section('meta-title')
Proposals
@stop

@section('page-title')
Proposal<span class="hidden-xs"> > {{ $proposal->title }}</span>
@stop

@section('content')

<div class="page-header">

    <h1 class="visible-xs">{{ $proposal->title }}</h1>
    <p>{{ $proposal->present()->description }}</p>

</div>

<div class="well">
Proposal created on {{ $proposal->present()->created_at }}<br />
@if ($proposal->isOpen())
    <strong>Voting is open</strong><br />
    The vote closes on {{ $proposal->present()->end_date }}

    {{ Form::open(array('method'=>'POST', 'route' => ['proposals.vote', $proposal->id], 'class'=>'')) }}

    <div class="radio">
        <label data-toggle="tooltip" data-placement="right" title="I am in favour of this proposal">
            {{ Form::radio('vote', '+1', (isset($memberVote) && $memberVote->vote == '+1')) }}
            +1
        </label>
    </div>
    <div class="radio">
        <label data-toggle="tooltip" data-placement="right" title="I have no strong opinion on this issue">
            {{ Form::radio('vote', '0', (isset($memberVote) && $memberVote->vote == '0')) }}
            0
        </label>
    </div>
    <div class="radio">
        <label data-toggle="tooltip" data-placement="right" title="I am not in favour of this proposal">
            {{ Form::radio('vote', '-1', (isset($memberVote) && $memberVote->vote == '-1')) }}
            -1
        </label>
    </div>
    <div class="radio">
        <label data-toggle="tooltip" data-placement="right" title="I am abstaining from this vote">
            {{ Form::radio('vote', 'abstain', (isset($memberVote) && $memberVote->abstain)) }}
            Abstain
        </label>
    </div>
    @if (isset($memberVote))
        {{ Form::submit('Change your Vote', array('class'=>'btn btn-primary')) }}
    @else
        {{ Form::submit('Vote', array('class'=>'btn btn-primary')) }}
    @endif
    {{ Form::close() }}

@else
    Voting has closed<br />
    The vote closed on {{ $proposal->present()->end_date }}<br />
    {{ $proposal->present()->outcome }}
@endif

</div>

<h3>Voters</h3>
<table class="table">
    <thead>
        <tr>
            <th>Member</th>
            <th>Vote</th>
        </tr>
    </thead>
@foreach ($memberVotes as $vote)
    <tbody>
        <tr>
            <td>{{ link_to_route('members.show', $vote->member->name, $vote->member->id) }}</td>
            <td>
            @if ($vote->abstain)
            Abstained
            @else
            {{ $vote->vote }}
            @endif
            </td>
        </tr>
    </tbody>
@endforeach
</table>

@stop