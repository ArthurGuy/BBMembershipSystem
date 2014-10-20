@extends('layouts.main')

@section('title')
Build Brighton Credit {{ $user->name }}
@stop

@section('content')

<div class="row page-header">
    <div class="col-xs-12 col-sm-6 col-md-10">
        <h1>Build Brighton Credit</h1>
        <p>
            There are a number services at Build Brighton that require payments, such as the laser fee,
            tuck shop and component store. You can maintain a balance here to pay for these services quickly and easily.
        </p>
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
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add Credit</h3>
            </div>
            <div class="panel-body">
            <p>Top up using Direct Debit or a credit/debit card payment</p>


            {{ Form::open(['method'=>'POST', 'href' => '', 'class'=>'form-inline js-multiPaymentForm']) }}
            {{ Form::hidden('reason', 'balance') }}
            {{ Form::hidden('stripe_token', '', ['class'=>'js-stripeToken']) }}
            {{ Form::hidden('redirect_url', route('account.bbcredit.index', [$user->id])) }}

            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {{ Form::input('number', 'amount', '10.00', ['class'=>'form-control js-amount', 'step'=>'0.01', 'required'=>'required']) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::select('source', ['gocardless'=>'Direct Debit', 'stripe'=>'Credit/Debit Card'], null, ['class'=>'form-control'])  }}
            </div>
            {{ Form::submit('Top up', array('class'=>'btn btn-primary')) }}
            <div class="has-feedback has-error">
                <span class="help-block"></span>
            </div>
            {{ Form::close() }}


<!--

            <h5>Direct Debit</h5>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id], 'class'=>'navbar-form')) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::hidden('source', 'gocardless') }}
                {{ Form::input('number', 'amount', '10.00', ['class'=>'form-control', 'step'=>'0.01', 'required'=>'required']) }}
                {{ Form::submit('Direct Debit', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}

            <h5>Credit / Debit Card</h5>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.stripe.store', $user->id], 'class'=>'navbar-form js-stripeForm')) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::hidden('stripe_token', '', ['class'=>'js-stripeToken']) }}
                {{ Form::hidden('redirect_url', route('account.bbcredit.index', [$user->id])) }}
                {{ Form::input('number', 'amount', '10.00', ['class'=>'form-control js-stripeAmount', 'step'=>'0.01', 'required'=>'required']) }}
                {{ Form::submit('Credit/Debit Card', array('class'=>'btn btn-primary js-stripeCheckout')) }}
                {{ Form::close() }}
-->
            </div>

            @if (Auth::user()->isAdmin())
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
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payments</h3>
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

@section('footer-js')

<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    var handler = StripeCheckout.configure({
        key: '@stripeKey',
        name: 'Build Brighton',
        currency: 'GBP',
        email: '{{ $user->email }}',
        token: function(token) {
            console.log(token);
            $('.js-stripeToken').val(token.id);
            $('.js-multiPaymentForm').submit();
        }
    });

    $('.js-stripeForm').on('submit', function(event) {
        event.preventDefault();
        event.target.checkValidity();

        var topUpAmount = ($('.js-stripeAmount').val() * 100);

        handler.open({
            description: 'Balance Top up',
            amount: topUpAmount
        });
    });

    var paymentRoutes = {
        stripe: '{{ route('account.payment.stripe.store', $user->id) }}',
        gocardless: '{{ route('account.payment.gocardless.create', $user->id) }}'
    };
    var multiPaymentFormChecked = false;
    $('.js-multiPaymentForm').on('submit', function(event) {

        //Clear the error messages
        $(this).find('.help-block').text('');

        var source = $('.js-multiPaymentForm [name=source] option:selected').val();

        //Update the form target
        $(this).attr('action', paymentRoutes[source]);

        //Validation rules
        if (source == 'stripe') {
            //Stripe is handled seperatly so stop this form post
            event.preventDefault();
            if (($(this).find('.js-amount').val() * 1) < 10) {
                $(this).find('.help-block').text("Because of processing fees the payment must be £10 or over when paying by card");
            } else {
                var topUpAmount = ($(this).find('.js-amount').val() * 100);

                    handler.open({
                        description: 'Balance Top up',
                        amount: topUpAmount
                    });
            }
        } else {
            //$(this).submit();
            //return true;
        }
    });
</script>

@stop