<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">There is a problem with your subscription</h3>
    </div>
    <div class="panel-body">
        @if ($user->payment_method == 'gocardless')
        @if (!empty($user->subscription_id))
        <p>
            Your direct debit payment has failed and we need you to make a manual payment.<br />
            You have a week to do this or your access to the space will be halted.
        </p>
        <p>
            {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id])) }}
            {{ Form::hidden('reason', 'subscription') }}
            {{ Form::hidden('source', 'gocardless') }}
            {{ Form::submit('Make a one off direct debit payment for &pound;'.round($user->monthly_subscription), array('class'=>'btn btn-primary')) }}
            {{ Form::close() }}
        </p>
        <p>
            We still have a record of a Direct Debit being setup, this may have been cancelled as well but we haven't yet been notified.
        </p>
        <p>
            If you want to make a cash payment instead please contact a trustee
        </p>
        @else
        <p>
            Something odd is going on as you shouldn't see this message. Please let a trustee know there is an issue with your account.
        </p>
        @endif
        @else
        <p>
            There is a problem with your subscription payment,
            this could be because you have cancelled or missed a monthly payment or it may be because bank records haven't been reconciled yet.<br />
            If you know you have missed a payment please make this ASAP or ideally change over to a direct debit payment.<br />
            If you have concerns or aren't sure please contact a trustee.<br />
            <br />
            <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a><br />
        </p>
        @endif

    </div>
</div>