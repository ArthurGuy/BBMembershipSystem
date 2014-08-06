<div class="row">
    <div class="page-header col-xs-12">
        <h1>Your Membership</h1>
        <a href="{{ route('account.edit', $user->id) }}" class="btn btn-info btn-sm">Edit Your Details</a>
    </div>
</div>


@if ($user->status == 'setting-up')

    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3 pull-left">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Continue setting up your account</h3>
                </div>
                <div class="panel-body">
                    <p>
                        Thank you for joining Build Brighton, to continue you need to setup a direct debit to pay the monthly subscription<br />
                        <small>If you want to change your monthly amount please <a href="{{ route('account.edit', $user->id) }}">edit your details</a></small>
                    </p>
                    <a href="{{ route('account.subscription.create', $user->id) }}" class="btn btn-primary">Setup a Direct Debit for &pound;{{ round($user->monthly_subscription) }}</a>
                </div>
            </div>
        </div>
    </div>

@else

<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-4 pull-right">
        <div class="panel panel-info">
            <div class="panel-body">
                Status:{{ User::statusLabel($user->status) }}<br />
                Payment Method: {{ $user->payment_method }}<br />
                Monthly Payment: &pound;{{ round($user->monthly_subscription) }}<br />
                @if ($user->last_subscription_payment->year > 0)
                    Last Payment: {{ $user->last_subscription_payment->toFormattedDateString() }}<br />
                @endif
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Inductions</h3>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Trained</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($inductions as $itemKey=>$item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>&pound;{{ $item->cost }}</td>
                    <td>
                        @if ($item->userInduction && ($item->userInduction->trained))
                            {{ $item->userInduction->trained->toFormattedDateString() }}
                        @elseif ($item->userInduction && $item->userInduction->paid)
                            Pending
                        @endif
                    </td>
                    <td>
                        @if (!$item->userInduction || ($item->userInduction && !$item->userInduction->paid))
                            {{ Form::open(array('method'=>'POST', 'route' => ['account.induction.store', $user->id])) }}
                            {{ Form::hidden('induction_key', $itemKey) }}
                            {{ Form::submit('Pay Now', array('class'=>'btn btn-primary btn-xs')) }}
                            {{ Form::close() }}
                        @elseif ($item->userInduction && $item->userInduction->paid)
                            Paid
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>

<div class="row">
    <div class="col-xs-12 col-lg-8 pull-left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Payments</h3>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Method</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
            @foreach ($user->payments as $payment)
                <tr>
                    <td>{{ $payment->reason }}</td>
                    <td>{{ $payment->source }}</td>
                    <td>{{ $payment->created_at->toFormattedDateString() }}</td>
                    <td>&pound;{{ $payment->amount }}</td>
                    <td>{{ $payment->status }}</td>
                </tr>
            @endforeach
            </table>
        </div>
    </div>
</div>



@if ($user->payment_method == 'gocardless')
<div class="row">
    <div class="col-xs-12 col-lg-4">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">Cancel</h3>
            </div>
            <div class="panel-body">
                {{ Form::open(array('method'=>'DELETE', 'route' => ['account.subscription.destroy', $user->id, 1])) }}

                {{ Form::submit('Cancel Your Subscription Payment', array('class'=>'btn btn-danger')) }}

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endif


@endif


@if (Auth::user()->isAdmin())
<div class="col-xs-12 col-md-6 col-lg-4">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">Update Subscription<span class="label label-danger pull-right">Admin</span></h3>
        </div>
        <div class="panel-body">
            {{ Form::model($user, array('method'=>'PUT', 'route' => ['account.alter-subscription', $user->id])) }}

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group {{ $errors->has('payment_method') ? 'has-error has-feedback' : '' }}">
                        {{ Form::label('payment_method', 'Payment Method') }}
                        {{ Form::select('payment_method', [''=>'']+$paymentMethods, null, ['class'=>'form-control']) }}
                        {{ $errors->first('payment_method', '<span class="help-block">:message</span>') }}
                        @if ($user->payment_method == 'gocardless')
                        <span class="help-block">Changing away from GoCardless will cancel the direct debit</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group {{ $errors->has('payment_day') ? 'has-error has-feedback' : '' }}">
                        {{ Form::label('payment_day', 'Payment Day') }}
                        {{ Form::select('payment_day', [''=>'']+$paymentDays, null, ['class'=>'form-control']) }}
                        {{ $errors->first('payment_day', '<span class="help-block">:message</span>') }}
                    </div>
                </div>
            </div>

            {{ Form::submit('Update', array('class'=>'btn btn-danger')) }}

            {{ Form::close() }}
        </div>
    </div>
</div>
@endif