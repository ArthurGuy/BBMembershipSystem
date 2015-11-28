@extends('layouts.main')

@section('meta-title')
    Members pending induction confirmation
@stop

@section('page-title')
    Members pending induction confirmation
@stop

@section('content')

<table class="table memberList">
    <tbody>
        @each('account.induction.index-row', $users, 'user', 'account.induction.index-empty')
    </tbody>
</table>
@stop