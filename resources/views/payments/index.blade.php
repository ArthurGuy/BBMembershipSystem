@extends('layouts.main')

@section('meta-title')
Payments
@stop

@section('page-title')
Payments
@stop

@section('page-action-buttons')
        <!--<a class="btn btn-secondary" href="{{ route('account.create') }}">Create a new Member</a>-->
    <a class="btn btn-secondary" href="{{ route('statement-import.create') }}">Upload Bank Statement</a>
@stop

@section('main-tab-bar')
<nav id="mainTabBar">
    <ul class="" role="tablist">
        <li class="active">
            {!! link_to_route('payments.index', 'All Payments') !!}
        </li>
        <li class="">
            {!! link_to_route('payments.overview', 'Overview') !!}
        </li>
        <li class="">
            {!! link_to_route('payments.sub-charges', 'Subscription Charges') !!}
        </li>
    </ul>
</nav>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 well">
        {!! Form::open(array('method'=>'GET', 'route' => ['payments.index'], 'class'=>'navbar-form navbar-left')) !!}
        {!! Form::select('date_filter', [''=>'All Time']+$dateRange, Request::get('date_filter', ''), ['class'=>'form-control js-advanced-dropdown', 'style'=>'margin-right:10px; width:150px;']) !!}
        {!! Form::select('member_filter', [''=>'All Members']+$memberList, Request::get('member_filter', ''), ['class'=>'form-control js-advanced-dropdown', 'style'=>'margin-right:10px; width:150px;']) !!}
        {!! Form::select('reason_filter', [''=>'All Reasons']+$reasonList, Request::get('reason_filter', ''), ['class'=>'form-control js-advanced-dropdown', 'style'=>'margin-right:10px; width:150px;']) !!}
        {!! Form::submit('Filter', array('class'=>'btn btn-default btn-sm')) !!}
        {!! Form::close() !!}
    </div>
</div>

{!! HTML::sortablePaginatorLinks($payments) !!}
<table class="table memberList">
    <thead>
        <tr>
            <th>{!! HTML::sortBy('created_at', 'Date', 'payments.index') !!}</th>
            <th>Member</th>
            <th>{!! HTML::sortBy('reason', 'Reason', 'payments.index') !!}</th>
            <th>{!! HTML::sortBy('source', 'Method', 'payments.index') !!}</th>
            <th>{!! HTML::sortBy('amount', 'Amount', 'payments.index') !!}</th>
            <th>{!! HTML::sortBy('reference', 'Reference', 'payments.index') !!}</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @each('payments.index-row', $payments, 'payment')
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" align="right">Total</td>
            <td><strong>&pound;{{ number_format($paymentTotal, 2) }}</strong></td>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>

{!! HTML::sortablePaginatorLinks($payments) !!}

    <div id="react-test"></div>

@stop

@section('footer-js')
    <script>

    </script>
@stop