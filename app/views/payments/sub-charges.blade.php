@extends('layouts.main')

@section('meta-title')
Payments - Sub Charges
@stop

@section('page-title')
Payments - Sub Charges
@stop

@section('main-tab-bar')
<nav id="mainTabBar">
    <ul class="" role="tablist">
        <li class="">
            {{ link_to_route('payments.index', 'All Payments') }}
        </li>
        <li class="">
            {{ link_to_route('payments.overview', 'Overview') }}
        </li>
        <li class="active">
            {{ link_to_route('payments.sub-charges', 'Subscription Charges') }}
        </li>
    </ul>
</nav>
@stop

@section('content')

<?php echo $charges->links(); ?>
<table class="table memberList">
    <thead>
        <tr>
            <th>Charge Date</th>
            <th>Member</th>
            <th>Payment Date</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Method</th>
        </tr>
    </thead>
    <tbody>
        @each('payments.sub-charges-row', $charges, 'charge')
    </tbody>
</table>

<?php echo $charges->links(); ?>

@stop
