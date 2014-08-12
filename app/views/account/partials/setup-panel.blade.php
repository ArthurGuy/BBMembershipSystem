<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Continue setting up your account</h3>
    </div>
    <div class="panel-body">
        <p class="lead">
            Thank you for joining Build Brighton, to continue you need to setup a direct debit to pay the monthly subscription
        </p>
        <p>
            <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a><br />
            <br />
            <small>If you want to change your monthly amount please <a href="{{ route('account.edit', $user->id) }}">edit your details</a></small><br />
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
        @if (Auth::user()->isAdmin())
        {{ Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) }}
        <p>
            <span class="label label-danger pull-right">Admin</span>
            Add a manual payment to this account and activate it
        </p>
        {{ Form::hidden('reason', 'subscription') }}
        {{ Form::select('source', ['other'=>'Other', 'paypal'=>'PayPal', 'cash'=>'Cash'], null, ['class'=>'form-control']) }}
        {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        @endif
    </div>
</div>