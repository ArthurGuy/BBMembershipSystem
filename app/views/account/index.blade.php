@extends('layouts.main')

@section('title')
Member List
@stop

@section('content')

<div class="row page-header">
    <div class="col-xs-12 col-sm-10">
        <h1>Members</h1>
    </div>
    <div class="col-xs-12 col-sm-2">
        <p><a href="{{ route('account.create') }}" class="btn btn-info btn-sm">Create a new member</a></p>
    </div>
</div>

{{ HTML::userPaginatorLinks($users) }}
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>{{ HTML::sortUsersBy('family_name', 'Name') }}</th>
            <th>Email</th>
            <th>{{ HTML::sortUsersBy('active', 'Active') }}</th>
            <th>{{ HTML::sortUsersBy('status', 'Status') }}</th>
            <th>{{ HTML::sortUsersBy('key_holder', 'Key Holder') }}</th>
            <th>{{ HTML::sortUsersBy('trusted', 'Trusted') }}</th>
            <th>Payment Method</th>
            <th>Subscription Expires</th>
            <!--<th>Payment</th>-->
        </tr>
    </thead>
    <tbody>
        @each('account.index-row', $users, 'user')
    </tbody>
</table>
{{ HTML::userPaginatorLinks($users) }}

@stop