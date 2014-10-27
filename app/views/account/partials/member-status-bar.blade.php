<ul class="nav nav-pills">
    <li>
        <p class="navbar-text">{{ $user->email }}</p>
    </li>
    <li>
        <p class="navbar-text">{{ HTML::statusLabel($user->status) }}</p>
    </li>

    <li>
        <p class="navbar-text">{{ HTML::spaceAccessLabel($user->active) }}</p>
    </li>

    @if ($user->keyFob())
    <li>
        <p class="navbar-text"><label class="label label-default">Key Fob ID: {{ $user->keyFob()->key_id }}</label></p>
    </li>
    @endif

    <li>
        <p class="navbar-text">{{ HTML::keyHolderLabel($user->key_holder) }}</p>
    </li>

    @if ($user->trusted)
    <li>
        <p class="navbar-text"><label class="label label-success">Trusted Member</label></p>
    </li>
    @endif

    @if (!$user->induction_completed)
    <li>
        <p class="navbar-text"><label class="label label-warning">Induction Pending</label></p>
    </li>
    @endif

    <li>
        <p class="navbar-text">Payment Method: {{ $user->present()->paymentMethod }}</p>
    </li>

    <li>
        <p class="navbar-text">Monthly Payment: &pound;{{ round($user->monthly_subscription) }}</p>
    </li>

    @if ($user->subscription_expires)
    <li>
        <p class="navbar-text">
            Subscription Expires:
            @if ($user->payment_method == 'standing-order')
                <span data-toggle="tooltip" data-placement="top" title="Confirmation of subscription payments happen once a month so this date may not be up to date">{{ $user->present()->subscriptionExpiryDate }}</span>
            @else
                {{ $user->present()->subscriptionExpiryDate }}
            @endif
        </p>
    </li>
    @endif
</ul>