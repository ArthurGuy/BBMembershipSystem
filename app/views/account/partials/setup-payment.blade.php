

    <p>
        <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a>
        <small><a href="{{ route('account.edit', $user->id) }}">Change your monthly amount</a></small><br />
        <br />
        The direct debit date will be the day you complete this process<br />
        You can cancel the direct debit at any point through this website, the <a href="https://gocardless.com/security" target="_blank">GoCardless</a>
        website (our payment processor) or your bank giving you full control over the payments. By switching you will also protected by the
        <a href="https://gocardless.com/direct-debit/guarantee/" target="_blank">direct debit guarantee.</a>
    </p>
    <p>
        You can also setup a PayPal subscription, this costs us a lot more so please only do this if you don't have a UK bank account.<br />
        Your PayPal email address you use <strong>must</strong> be the same one you used when registering, if you don't want to use this one please contact us <strong>before</strong> setting up a subscription.
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
    <p>
        If neither of these options are suitable please send us an email explaining the situation <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>
    </p>
    @if (Auth::user()->isAdmin())
    {{ Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) }}
    <p>
        <span class="label label-danger pull-right">Admin</span>
        Add a manual payment (with todays date) to this account and activate it
    </p>
    {{ Form::hidden('reason', 'subscription') }}
    {{ Form::select('source', ['other'=>'Other', 'paypal'=>'PayPal', 'cash'=>'Cash', 'standing-order'=>'Standing Order'], null, ['class'=>'form-control']) }}
    {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
    {{ Form::close() }}
    @endif
