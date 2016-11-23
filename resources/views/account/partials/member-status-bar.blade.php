<div class="row">
    <div class="col-xs-12 col-sm-8">
        <ul class="nav nav-pills">
            <li>
                <p class="navbar-text">{!! HTML::statusLabel($user->status) !!}</p>
            </li>

            <li>
                <p class="navbar-text">{!! HTML::spaceAccessLabel($user->active) !!}</p>
            </li>

            @if ($user->keyFob())
            <li>
                <p class="navbar-text"><label class="label label-default">Key Fob ID: {{ $user->keyFob()->key_id }}</label></p>
            </li>
            @endif

            @if ($user->active)
            <li>
                <p class="navbar-text">{!! HTML::keyHolderLabel($user->key_holder && $user->trusted) !!}</p>
            </li>
            @endif

            @if (!$user->key_holder && $user->trusted)
            <li>
                <p class="navbar-text"><label class="label label-success">Trusted Member</label></p>
            </li>
            @endif

            @if ($user->isInducted())
                <li>
                    <p class="navbar-text"><label class="label label-success" data-toggle="tooltip" data-placement="top" title="Confirmed by {{ $user->inductedBy()->name }}">Inducted</label></p>
                </li>
            @endif

            @if (0 && $user->active)
            <li>
                <p class="navbar-text">{{ $user->present()->subscriptionDetailLine }}</p>
            </li>
            @endif

            @if ($user->active && $user->subscription_expires)
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
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="memberSubAmount">
            <p class="navbar-text">
                Balance: {{ $memberBalance }} <br />
                {{ $user->present()->subscriptionDetailLine }}
                @if ($user->canMemberChangeSubAmount())
                    <small><a href="#" class="js-show-alter-subscription-amount" title="Change Amount">Change</a></small>
                @endif
            </p>
            @if ($user->canMemberChangeSubAmount())
                {!! Form::open(array('method'=>'POST', 'class'=>'form-inline hidden js-alter-subscription-amount-form', 'style'=>'display:inline-block; margin-bottom:20px;', 'route' => ['account.update-sub-payment', $user->id])) !!}
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::text('monthly_subscription', round($user->monthly_subscription), ['class'=>'form-control']) !!}
                </div>
                {!! Form::submit('Update', array('class'=>'btn btn-default')) !!}<br><br>
                {!! Form::close() !!}

                @if ($user->payment_method == 'gocardless-variable')
                    {!! Form::open(array('method'=>'POST', 'class'=>'form-inline hidden js-alter-subscription-amount-form', 'style'=>'display:inline-block; margin-bottom:20px;', 'route' => ['account.update-sub-method', $user->id])) !!}
                        {!! Form::hidden('payment_method', 'balance') !!}
                        {!! Form::submit('Change to balance payment', array('class'=>'btn btn-default')) !!}<br><br>
                    {!! Form::close() !!}
                @endif
            @endif
        </div>
    </div>
</div>

