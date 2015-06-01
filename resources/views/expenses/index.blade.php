@extends('layouts.main')

@section('meta-title')
    Expenses
@stop

@section('page-title')
    Expenses
@stop


@section('content')

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Reclaiming Expenses</h3></div>
            <div class="panel-body">
                <p>
                    If you want to spend less than &pound;10 on some consumables or a replacement part please go ahead and do it,
                    if you want to spend more than this please contact the <a href="trustees@buildbrighton.com">trustees@buildbrighton.com</a>
                    first to ensure we have the money available.
                </p>
                <p>
                    If you want to reclaim an expense you can do this through your membership page. You will need the receipt,
                    either a pdf or photo, this will need to be submitted along with a description, amount and the date.
                </p>
            </div>
        </div>
    </div>

{!! HTML::sortablePaginatorLinks($expenses) !!}
<table class="table memberList">
    <thead>
        <tr>
            <th>{!! HTML::sortBy('expense_date', 'Expense Date', 'expenses.index') !!}</th>
            <th>{!! HTML::sortBy('user_id', 'Member', 'expenses.index') !!}</th>
            <th>{!! HTML::sortBy('category', 'Category', 'expenses.index') !!}</th>
            <th>{!! HTML::sortBy('description', 'Description', 'expenses.index') !!}</th>
            <th>{!! HTML::sortBy('amount', 'Amount', 'expenses.index') !!}</th>
            @if (Auth::user()->hasRole('admin'))
            <th>Receipt</th>
            @endif
            <th></th>
        </tr>
    </thead>
    <tbody>
        @each('expenses.index-row', $expenses, 'expense')
    </tbody>
</table>

{!! HTML::sortablePaginatorLinks($expenses) !!}

    <div id="react-test"></div>

@stop