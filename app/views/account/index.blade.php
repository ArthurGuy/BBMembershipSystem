<div class="row page-header">
    <div class="col-xs-12 col-sm-10">
        <h1>Members</h1>
    </div>
    <div class="col-xs-12 col-sm-2">
        <p><a href="{{ route('account.create') }}" class="btn btn-info btn-sm">Create a new member</a></p>
    </div>
</div>

<div>
    Active Members: {{ $numActiveUsers }}
</div>

<?php echo $users->links(); ?>
<table class="table">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Active</th>
            <th>Status</th>
            <th>Key Holder</th>
            <th>Induction Complete</th>
            <th>Payment Method</th>
            <th>Subscription Expires</th>
            <th>Payment</th>
        </tr>
    </thead>
@foreach ($users as $user)
    <tbody>
        <tr>
            <td>
                @if ($user->profile_photo)
                <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="100" height="100" />
                @else
                <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="100" height="100" />
                @endif
            </td>
            <td>
                <a href="{{ route('account.show', $user->id) }}">{{ $user->name }}</a>
                @if ($user->hasRole('admin'))
                <span class="label label-danger">Admin</span>
                @endif
            </td>
            <td>{{ $user->email }}</td>
            <td>
                @if ($user->active)
                <span class="glyphicon glyphicon-ok"></span>
                @else
                <span class="glyphicon glyphicon-remove"></span>
                @endif
            </td>
            <td>{{ User::statusLabel($user->status) }}</td>
            <td>
                @if($user->key_holder)
                    Yes
                @else
                    No
                @endif
            </td>
            <td>
                @if ($user->induction_completed)
                <span class="glyphicon glyphicon-ok"></span>
                @else
                <span class="glyphicon glyphicon-remove"></span>
                @endif
            </td>
            <td>{{ $user->payment_method }}</td>
            <td>
                @if ($user->subscription_expires->year > 0)
                    {{ $user->subscription_expires->toFormattedDateString() }}
                @else
                    -
                @endif
            </td>
            <td>
                {{ Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) }}
                {{ Form::hidden('reason', 'subscription') }}
                {{ Form::select('source', ['other'=>'Other', 'paypal'=>'PayPal', 'cash'=>'Cash'], null, ['class'=>'form-control']) }}
                {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </td>
        </tr>
    </tbody>
@endforeach
</table>
<?php echo $users->links(); ?>