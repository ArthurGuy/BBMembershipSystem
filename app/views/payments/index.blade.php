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
        <li class="@if (Request::get('showLeft', 0) == '0') active @endif">
            {{ link_to_route('payments.index', 'All Payments', []) }}
        </li>
        <li class="@if (Request::get('keyDeposits', 0) == 1) active @endif">
            {{ link_to_route('payments.index', 'Key Deposits', ['keyDeposits'=>1]) }}
        </li>
        <li class="@if (Request::get('boxDeposits', 0) == 1) active @endif">
            {{ link_to_route('payments.index', 'Box Deposits', ['boxDeposits'=>1]) }}
        </li>
        <li class="@if (Request::get('boxDeposits', 0) == 1) active @endif">
            {{ link_to_route('payments.index', 'Balance Payments', ['boxDeposits'=>1]) }}
        </li>
    </ul>
</nav>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 well">
        {{ Form::open(array('method'=>'GET', 'route' => ['payments.index'], 'class'=>'navbar-form navbar-left')) }}
        {{ Form::select('date_filter', [''=>'All Time']+$dateRange, null, ['class'=>'form-control']) }}
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