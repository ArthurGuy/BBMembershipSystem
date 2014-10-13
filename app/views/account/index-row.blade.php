<tr>
    <td>
        @if ($user->profile->profile_photo)
        <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="100" height="100" class="img-circle" />
        @else
        <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="100" height="100" class="img-circle" />
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
    <td>{{ HTML::statusLabel($user->status) }}</td>
    <td>
        @if($user->key_holder)
            <span class="glyphicon glyphicon-ok"></span>
        @else
            <span class="glyphicon glyphicon-remove"></span>
        @endif
    </td>
    <td>
        @if ($user->trusted)
        <span class="glyphicon glyphicon-ok"></span>
        @else
        <span class="glyphicon glyphicon-remove"></span>
        @endif
    </td>
    <td>{{ $user->present()->paymentMethod }}</td>
    <td>{{ $user->present()->subscriptionExpiryDate }}</td>
    <!--
    <td>
        {{ Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) }}
        {{ Form::hidden('reason', 'subscription') }}
        {{ Form::select('source', ['other'=>'Other', 'paypal'=>'PayPal', 'cash'=>'Cash'], null, ['class'=>'form-control']) }}
        {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
    </td>
    -->
</tr>