@extends('layouts.main')

@section('meta-title')
Payments
@stop

@section('page-title')
Payments
@stop

@section('content')

{{ HTML::sortablePaginatorLinks($payments) }}
<table class="table memberList">
    <thead>
        <tr>
            <th>{{ HTML::sortPaymentsBy('created_at', 'Date') }}</th>
            <th>Member</th>
            <th>{{ HTML::sortPaymentsBy('reason', 'Reason') }}</th>
            <th>{{ HTML::sortPaymentsBy('method', 'Method') }}</th>
            <th>{{ HTML::sortPaymentsBy('amount', 'Amount') }}</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @each('payments.index-row', $payments, 'payment')
    </tbody>
</table>

@stop