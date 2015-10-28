

    <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit</a>
    <br /><br />
    <p>
        Your subscription payments will be taken on the day you complete this process unless stated otherwise on this page.<br />
        You can cancel the Direct Debit at any point through this website or your bank giving you full control over the payments.
        It will also protected by the <a href="https://gocardless.com/direct-debit/guarantee/" target="_blank">Direct Debit guarantee.</a>
    </p>
    <p>
        You can also setup a PayPal subscription although this costs us a lot more so we'd rather you didn't,
        please only do this if you don't have a UK bank account.<br />
        <strong>Your primary PayPal email address must be known to us</strong>, if its not the one you used when registering
        <a href="{{ route('account.edit', $user->id) }}">enter an alternate email address now</a>.
        {!! Form::open(['method'=>'post', 'url'=>'https://www.paypal.com/cgi-bin/webscr']) !!}
        {!! Form::submit('Setup a PayPal Subscription', ['class'=>'btn']) !!}
        {!! Form::hidden('cmd', '_xclick-subscriptions') !!}
        {!! Form::hidden('business', 'board@buildbrighton.com') !!}
        {!! Form::hidden('item_name', 'Build Brighton Membership') !!}
        {!! Form::hidden('no_note', '1') !!}
        {!! Form::hidden('bn', 'PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest') !!}
        {!! Form::hidden('currency_code', 'GBP') !!}
        {!! Form::hidden('a3', "$user->monthly_subscription" ) !!}
        {!! Form::hidden('p3', '1') !!}
        {!! Form::hidden('t3', 'M') !!}
        {!! Form::hidden('lc', 'GB') !!}
        {!! Form::hidden('src', '1') !!}
        {!! Form::hidden('no_note', '1') !!}
        {!! Form::hidden('no_shipping', '1') !!}
        {!! Form::hidden('hosted_button_i', '3H4YABLMVW6RC') !!}
        {!! Form::close() !!}
    </p>
    <p class="hidden">
        If neither of these options are suitable please send us an email explaining the situation <a href="mailto:trustees@buildbrighton.com">trustees@buildbrighton.com</a>
    </p>
