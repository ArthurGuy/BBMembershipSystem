@extends('layouts.main')

@section('meta-title')
Member List
@stop

@section('page-title')
Members
@stop

@section('main-tab-bar')
<nav id="mainTabBar">
    <ul class="" role="tablist">
        <li class="@if (Request::get('showLeft', 0) == '0') active @endif">
            {{ link_to_route('account.index', 'Active Members', ['showLeft'=>0]) }}
        </li>
        <li class="@if (Request::get('showLeft', 0) == 1) active @endif">
            {{ link_to_route('account.index', 'Old Members', ['showLeft'=>1]) }}
        </li>
    </ul>
</nav>
@stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        <p class="">
            <a href="{{ route('account.create') }}" class="btn btn-info btn-sm">Create a new member</a>
            <a href="{{ route('notificationemail.create') }}" class="btn btn-info btn-sm">Email Members</a>
        </p>
    </div>
</div>

{{ HTML::userPaginatorLinks($users) }}
<table class="table memberList">
    <thead>
        <tr>
            <th></th>
            <th>{{ HTML::sortUsersBy('family_name', 'Name') }}</th>
            <th>{{ HTML::sortUsersBy('status', 'Status') }}</th>
            <th class="hidden-xs">{{ HTML::sortUsersBy('key_holder', 'Key Holder') }}</th>
            <th class="hidden-xs">{{ HTML::sortUsersBy('trusted', 'Trusted') }}</th>
            <th class="hidden-xs">Subscription</th>
        </tr>
    </thead>
    <tbody>
        @each('account.index-row', $users, 'user')
    </tbody>
</table>
{{ HTML::userPaginatorLinks($users) }}
@stop