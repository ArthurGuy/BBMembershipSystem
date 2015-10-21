<tr>
    <td class="profilePhotoCol">
        {!! HTML::memberPhoto($user->profile, $user->hash, 100, 'img-circle profilePhoto') !!}
    </td>
    <td>
        <a href="{{ route('account.show', $user->id) }}">{{ $user->name }}</a>
        @if ($user->hasRole('admin'))
        <span class="label label-danger">Admin</span>
        @endif
        <br />
        {{ $user->email }}
    </td>
    <td>
        {!! HTML::statusLabel($user->status) !!}
        @if ($user->profile->new_profile_photo)
            <br /><span class="label label-info">Photo to approve</span>
        @endif
    </td>
    <td class="hidden-xs">
        @if($user->key_holder)
            <i class="material-icons" title="Key Holder">vpn_key</i>
        @endif

    </td>
    <td class="hidden-xs">
        @if ($user->trusted)
            <i class="material-icons" title="Trusted">verified_user</i>
        @endif
    </td>
    <td class="hidden-xs">
        {{ $user->present()->paymentMethod }}<br />
        Expires: {{ $user->present()->subscriptionExpiryDate }}
    </td>
    <!--
    <td>
        {!! Form::open(array('method'=>'POST', 'class'=>'well form-inline', 'route' => ['account.payment.store', $user->id])) !!}
        {!! Form::hidden('reason', 'subscription') !!}
        {!! Form::select('source', ['other'=>'Other', 'paypal'=>'PayPal', 'cash'=>'Cash'], null, ['class'=>'form-control']) !!}
        {!! Form::submit('Record A &pound;'.round($user->monthly_subscription).' Payment', array('class'=>'btn btn-default')) !!}
        {!! Form::close() !!}
    </td>
    -->
</tr>