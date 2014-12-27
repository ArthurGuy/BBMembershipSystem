@extends('layouts.main')

@section('meta-title')
Payments
@stop

@section('page-title')
Payments
@stop

@section('main-tab-bar')
<nav id="mainTabBar">
    <ul class="" role="tablist">
        <li class="active">
            {{ link_to_route('payments.index', 'All Payments') }}
        </li>
        <li class="">
            {{ link_to_route('payments.overview', 'Overview') }}
        </li>
    </ul>
</nav>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 well">
        {{ Form::open(array('method'=>'GET', 'route' => ['payments.index'], 'class'=>'navbar-form navbar-left')) }}
        {{ Form::select('date_filter', [''=>'All Time']+$dateRange, Request::get('date_filter', ''), ['class'=>'form-control']) }}
        {{ Form::submit('Filter', array('class'=>'btn btn-default btn-sm')) }}
        {{ Form::close() }}
    </div>
</div>

{{ HTML::sortablePaginatorLinks($payments) }}
<table class="table memberList">
    <thead>
        <tr>
            <th>{{ HTML::sortBy('created_at', 'Date', 'payments.index') }}</th>
            <th>Member</th>
            <th>{{ HTML::sortBy('reason', 'Reason', 'payments.index') }}</th>
            <th>{{ HTML::sortBy('source', 'Method', 'payments.index') }}</th>
            <th>{{ HTML::sortBy('amount', 'Amount', 'payments.index') }}</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @each('payments.index-row', $payments, 'payment')
    </tbody>
</table>
{{ HTML::sortablePaginatorLinks($payments) }}

@stop