{{ Form::open(['method'=>'POST', 'href' => '', 'class'=>'form-inline js-multiPaymentForm']) }}
        {{ Form::hidden('reason', $reason) }}
        {{ Form::hidden('display_reason', $displayReason, ['class'=>'js-paymentDescription']) }}
        {{ Form::hidden('stripe_token', '', ['class'=>'js-stripeToken']) }}
        {{ Form::hidden('return_path', $returnPath) }}

        @if ($amount != null)
            {{ Form::hidden('amount', $amount, ['class'=>'js-amount']) }}
        @else
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {{ Form::input('number', 'amount', '10.00', ['class'=>'form-control js-amount', 'step'=>'0.01', 'required'=>'required']) }}
                </div>
            </div>
        @endif
        <div class="form-group">
            {{ HTML::paymentFormMethodDropdown($methods) }}
        </div>
        {{ Form::submit($buttonLabel, array('class'=>'btn btn-primary')) }}
        <div class="has-feedback has-error">
            <span class="help-block"></span>
        </div>
{{ Form::close() }}