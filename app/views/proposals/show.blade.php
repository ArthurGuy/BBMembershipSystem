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
@if ($proposal->isOpen())
    <strong>Voting is open</strong><br />


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
    <p>
    <br />
    The vote closes on {{ $proposal->present()->end_date }}
    </p>
@else
    Voting has closed<br />
    The vote closed at the end of {{ $proposal->present()->end_date }}<br />
    {{ $proposal->present()->outcome }}
@endif

</div>


@stop