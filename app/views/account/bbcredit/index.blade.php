@extends('layouts.main')

@section('meta-title')
Build Brighton Balance {{ $user->name }}
@stop

@section('page-title')
Build Brighton Balance
@stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        <p>
            This is your Build Brighton Balance, it can be used to pay for your time on the laser, storage boxes and in the future many other things as well.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add Credit</h3>
            </div>
            <div class="panel-body">
                <p>Top up using Direct Debit or a credit/debit card payment</p>

                @include('partials/payment-form', ['reason'=>'balance', 'returnPath'=>route('account.balance.index', [$user->id], false), 'amount'=>null, 'buttonLabel'=>'Top Up', 'displayReason'=>'Balance Payment', 'methods'=>['gocardless', 'stripe']])

                <p>
                    Cash top ups can be made to a trustee in the space
                </p>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <h3 class="panel-title">Balance</h3>
                </div>
                <div class="panel-body">
                    <span class="credit-figure">{{ $userBalance }}</span>
                </div>
            </div>
        </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Balance Payment History</h3>
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
