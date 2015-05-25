@extends('layouts.main')

@section('meta-title')
    Expenses
@stop

@section('page-title')
    Expenses
@stop


@section('content')


{{ HTML::sortablePaginatorLinks($expenses) }}
<table class="table memberList">
    <thead>
        <tr>
            <th>{{ HTML::sortBy('expense_date', 'Expense Date', 'expenses.index') }}</th>
            <th>{{ HTML::sortBy('user_id', 'Member', 'expenses.index') }}</th>
            <th>{{ HTML::sortBy('category', 'Category', 'expenses.index') }}</th>
            <th>{{ HTML::sortBy('description', 'Description', 'expenses.index') }}</th>
            <th>{{ HTML::sortBy('amount', 'Amount', 'expenses.index') }}</th>
            <th>Receipt</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @each('expenses.index-row', $expenses, 'expense')
    </tbody>
</table>

{{ HTML::sortablePaginatorLinks($expenses) }}

    <div id="react-test"></div>

@stop