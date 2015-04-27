<div class="panel panel-danger">
    <div class="panel-heading">
        <h3 class="panel-title">There is a problem with your subscription</h3>
    </div>
    <div class="panel-body">
        @if ($user->payment_method == 'gocardless')
            @if (!empty($user->subscription_id))
            <p>
                Your direct debit payment has failed and we need you to make a manual payment.<br />
                Please start by migrating to our new direct debit system
            </p>
            <p>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.gocardless-migrate'])) }}
                {{ Form::submit('Setup a variable Direct Debit', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}
            </p>
            @else
            <p>
                Something odd is going on as you shouldn't see this message. Please let a trustee know there is an issue with your membership.
            </p>
            @endif
        @elseif ($user->payment_method == 'gocardless-variable')
            <p>
                Your latest subscription payment has failed and your account has been temporarily suspended.<br />
                You can retry your direct debit payment now.
                @include('partials/payment-form', ['reason'=>'subscription', 'displayReason'=>'Retry payment', 'returnPath'=>route('account.show', [$user->id], false), 'amount'=>round($user->monthly_subscription), 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless', 'balance', 'stripe']])
            </p>
        @else
        <p>
            There is a problem with your subscription payment,
            this could be because you have cancelled, are in the process of switching payment methods,
            have missed a monthly payment or it may be because bank records haven't been reconciled yet.<br />
            If you know you have missed a payment please make this ASAP or ideally change over to a direct debit payment.<br />
            If you have concerns or aren't sure please contact a trustee.<br />
            <br />
            <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a>
            <small><a href="#" class="js-show-alter-subscription-amount">Change your monthly amount</a></small>
            {{ Form::open(array('method'=>'POST', 'class'=>'form-inline hidden js-alter-subscription-amount-form', 'style'=>'display:inline-block', 'route' => ['account.update-sub-payment', $user->id])) }}
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {{ Form::text('monthly_subscription', round($user->monthly_subscription), ['class'=>'form-control']) }}
            </div>
            {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
            {{ Form::close() }}
        </p>
        <p>
            You can also setup a PayPal subscription, this costs us a lot more so please only do this if you don't have a UK bank account
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
        {{ Form::select('source', ['other'=>'Other', 'cash'=>'Cash', 'paypal'=>'PayPal', 'standing-order'=>'Standing Order'], null, ['class'=>'form-control']) }}
        {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        @endif

    </div>
</div>