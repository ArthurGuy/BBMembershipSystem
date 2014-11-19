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
    <!--
    <div class="radio">
        <label data-toggle="tooltip" data-placement="right" title="I am abstaining from this vote">
            {{ Form::radio('vote', 'abstain', (isset($memberVote) && $memberVote->abstain)) }}
            Abstain
        </label>
    </div>
    -->
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
@elseif (!$proposal->hasStarted())
    Voting hasn't started yet.<br />
    Open for voting on {{ $proposal->start_date->format('jS M') }}
@else
    Voting has closed<br />
    The vote closed at the end of {{ $proposal->present()->end_date }}<br />
    {{ $proposal->present()->outcome }}
@endif

</div>


@if ($proposal->id == 3)
<div class="well">
    <p>
    If you pledged money towards the new laser cutter please make that payment now.
    This money will be record against your account for use against future laser cutter fees, it will appear as a credit top up
    </p>
    {{ Form::open(['method'=>'POST', 'href' => '', 'class'=>'form-inline js-multiPaymentForm']) }}
        {{ Form::hidden('reason', 'balance') }}
        {{ Form::hidden('stripe_token', '', ['class'=>'js-stripeToken']) }}
        {{ Form::hidden('return_path', route('proposals.show', [$proposal->id], false)) }}
        {{ Form::hidden('reference', 'laser-cutter') }}

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {{ Form::input('number', 'amount', '50.00', ['class'=>'form-control js-amount', 'step'=>'1', 'required'=>'required']) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::select('source', ['gocardless'=>'Direct Debit'], null, ['class'=>'form-control'])  }}
        </div>
        {{ Form::submit('Make your pledge payment now', array('class'=>'btn btn-primary')) }}
        <div class="has-feedback has-error">
            <span class="help-block"></span>
        </div>
    {{ Form::close() }}
    If you want to make a payment by bank transfer please use the reference <strong>{{ Auth::user()->id }}-BALANCE-LASER_CUTTER</strong>
</div>
@endif


@stop