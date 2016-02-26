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

                <div class="paymentModule" data-reason="balance" data-display-reason="Balance Payment" data-button-label="Top Up" data-methods="gocardless,stripe"></div>

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
                    <span class="credit-figure {{ $userBalanceSign }}">{{ $userBalance }}</span>
                </div>
            </div>
        </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Request a refund from your balance</h3>
            </div>
            <div class="panel-body">
                {!! Form::open(array('method'=>'POST', 'route' => ['account.balance.withdrawal', $user->id], 'class'=>'form-inline')) !!}
                <p>
                    Please fill in the amount you want to withdraw and the account number and sortcode of the bank you want the money to go to.<br />
                    We can only refund money reclaimed through expense submissions, anything else would be at the discretion of the
                    <a href="mailto:trustees@buildbrighton.com">trustees</a>.
                </p>

                <div class="form-group">
                    {!! Form::label('amount', 'Amount') !!}
                    {!! Form::input('number', 'amount', $rawBalance, ['class'=>'form-control', 'placeholder'=>'20', 'min'=>'1', 'max' => $rawBalance, 'step' => '0.01']) !!}
                    <span class="help-block"></span>
                </div>

                <div class="form-group">
                    {!! Form::label('account_number', 'Account Number') !!}
                    {!! Form::input('text', 'account_number', null, ['class'=>'form-control', 'placeholder' => '12345678', 'pattern'=>'[0-9]{8}']) !!}
                    <span class="help-block"></span>
                </div>

                <div class="form-group">
                    {!! Form::label('sort_code', 'Sort Code') !!}
                    {!! Form::input('text', 'sort_code', null, ['class'=>'form-control', 'placeholder' => '12-34-56', 'pattern'=>'[0-9]{2}-[0-9]{2}-[0-9]{2}']) !!}
                    <span class="help-block"></span>
                </div>
                <div class="form-group">
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                </div>

                <p>When you submit this request it will be forwarded to the trustees who will aim to action it within the new few weeks.</p>

                {!! Form::close() !!}
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
            {!! $payments->render() !!}
            </div>
        </div>
    </div>
</div>

@stop
