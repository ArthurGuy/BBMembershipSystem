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