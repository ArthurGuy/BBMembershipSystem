@extends('layouts.main')

@section('meta-title')
Build Brighton Credit {{ $user->name }}
@stop

@section('page-title')
Build Brighton Credit
@stop

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-10">
        <p>
            There are a number services at Build Brighton that require payments, such as the laser fee,
            tuck shop and component store. You can maintain a balance here to pay for these services quickly and easily.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add Credit</h3>
            </div>
            <div class="panel-body">
            <p>Top up using Direct Debit or a credit/debit card payment</p>


            @include('partials/payment-form', ['reason'=>'balance', 'returnPath'=>route('account.bbcredit.index', [$user->id], false), 'amount'=>null, 'buttonLabel'=>'Top Up', 'displayReason'=>'Balance Payment', 'methods'=>['gocardless', 'stripe']])

            </div>

            @if (Auth::user()->isAdmin() && 0)
            <div class="panel-footer">
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.store', $user->id], 'class'=>'navbar-form')) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::text('amount', '5.00', ['class'=>'form-control']) }}
                {{ Form::select('source', ['cash'=>'Cash'], null, ['class'=>'form-control']) }}
                {{ Form::submit('Record Top up', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </div>
            @endif
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-2">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <h3 class="panel-title">Balance</h3>
                </div>
                <div class="panel-body">
                    <span class="credit-figure">{{ $user->present()->cashBalance }}</span>
                </div>
            </div>
        </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payment History</h3>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Reason</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($payments as $payment)
                <tr class="{{ $payment->present()->balanceRowClass }}">
                    <td>{{ $payment->present()->reason }}</td>
                    <td>{{ $payment->present()->method }}</td>
                    <td>{{ $payment->present()->date }}</td>
                    <td>{{ $payment->present()->balanceAmount }}</td>
                    <td>{{ $payment->present()->status }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="panel-footer">
            <?php echo $payments->links(); ?>
            </div>
        </div>
    </div>
</div>

@stop
