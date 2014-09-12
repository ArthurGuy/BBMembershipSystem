<ul class="nav nav-pills">
    <li>
        <p class="navbar-text">{{ User::statusLabel($user->status) }}</p>
    </li>
    <li>
        <p class="navbar-text">

            @if ($user->active)
            <label class="label label-success">Access to the space</label><br />
            @else
            <label class="label label-danger">No access to he space</label><br />
            @endif
        </p>
    </li>
    @if ($user->keyFob())
    <li>
        <p class="navbar-text"><label class="label label-default">Key Fob ID: {{ $user->keyFob()->key_id }}</label></p>
    </li>
    @endif
    <li>
        <p class="navbar-text">
            @if ($user->key_holder)
            <label class="label label-success">Key Holder</label><br />
            @else
            <label class="label label-default">Key Holder: not yet</label><br />
            @endif
        </p>
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
        <p class="navbar-text">Subscription Expires: {{ $user->present()->subscriptionExpiryDate }}</p>
    </li>
    @endif
</ul>