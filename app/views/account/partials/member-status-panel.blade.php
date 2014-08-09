<div class="panel panel-info">
    <div class="panel-body">

        Status: {{ User::statusLabel($user->status) }}<br />

        @if ($user->active)
        Access to the space: <label class="label label-success">Granted</label><br />
        @else
        Access to the space: <label class="label label-danger">Denied</label><br />
        @endif

        @if ($user->induction_completed)
        Induction: Completed<br />
        @else
        Induction: <label class="label label-warning">Pending</label><br />
        @endif

        @if ($user->trusted)
        Trusted Member<br />
        @endif

        @if ($user->key_holder)
        Key Holder: <label class="label label-success">Yes</label><br />
        @else
        Key Holder: <label class="label label-default">Not yet</label><br />
        @endif


        Payment Method: {{ $user->payment_method }}<br />
        Monthly Payment: &pound;{{ round($user->monthly_subscription) }}<br />
        Subscription Expires: {{ $user->subscription_expires->toFormattedDateString() }}<br />
    </div>
</div>