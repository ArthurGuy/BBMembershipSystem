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