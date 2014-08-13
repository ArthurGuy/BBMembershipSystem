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
            this could be because you have cancelled, are in the process of switching payment methods, have missed a monthly payment or it may be because bank records haven't been reconciled yet.<br />
            If you know you have missed a payment please make this ASAP or ideally change over to a direct debit payment.<br />
            If you have concerns or aren't sure please contact a trustee.<br />
            <br />
            <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a><br />
        </p>
        <p>
            You can also setup a PaySal subscription, this costs us a lot more so please only do this if you don't have a UK bank account
            {{ Form::open(['method'=>'post', 'url'=>'https://www.paypal.com/cgi-bin/webscr']) }}
            {{ Form::submit('Setup a PayPal Subscription', ['class'=>'btn']) }}
            {{ Form::hidden('cmd', '_xclick-subscriptions') }}
            {{ Form::hidden('business', 'info@buildbrighton.com') }}
            {{ Form::hidden('item_name', 'Build Brighton Membership') }}
            {{ Form::hidden('no_note', '1') }}
            {{ Form::hidden('bn', 'PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest') }}
            {{ Form::hidden('currency_code', 'GBP') }}
            {{ Form::hidden('a3', "$user->monthly_subscription" ) }}
            {{ Form::hidden('p3', '1') }}
            {{ Form::hidden('t3', 'M') }}
            {{ Form::hidden('lc', 'GB') }}
            {{ Form::hidden('hosted_button_i', '3H4YABLMVW6RC') }}
            {{ Form::close() }}
        </p>
        @endif

        @if (Auth::user()->isAdmin())
        {{ Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) }}
        <p>
            <span class="label label-danger pull-right">Admin</span>
            Add a manual payment to this account
        </p>
        {{ Form::hidden('reason', 'subscription') }}
        {{ Form::select('source', ['other'=>'Other', 'cash'=>'Cash', 'paypal'=>'PayPal'], null, ['class'=>'form-control']) }}
        {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        @endif

    </div>
</div>