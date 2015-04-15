@extends('layouts.main')

@section('meta-title')
{{{ $user->name }}} - Manage your membership
@stop

@section('page-title')
    {{{ $user->name }}}<br />
    <small>{{{ $user->email }}}</small>
@stop

@section('page-key-image')
    {{ HTML::memberPhoto($user->profile, $user->hash, 100, '') }}
@stop


@section('page-action-buttons')
    <a class="btn btn-secondary" href="{{ route('account.edit', [$user->id]) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
    <a class="btn btn-secondary" href="{{ route('members.show', [$user->id]) }}"><span class="glyphicon glyphicon-user"></span> View Profile</a>
@stop

@section('content')

@include('account.partials.member-status-bar')

@include('account.partials.member-admin-action-bar')

@if (count($user->getAlerts()) > 0)
<div class="alert alert-warning" role="alert">
    <ul>
        @foreach ($user->getAlerts() as $alert)
            @if ($alert == 'missing-profile-photo')
                <li><strong>Missing profile photo</strong>, a photo is required for all trusted members - <a href="{{ route('account.profile.edit', [$user->id]) }}" class="alert-link">upload a photo</a></li>
            @endif
            @if ($alert == 'missing-phone')
                <li><strong>No phone number</strong>, please enter a phone number - we need this in case we have to get in contact with you - <a href="{{ route('account.edit', [$user->id]) }}" class="alert-link">edit your profile</a></li>
            @endif
        @endforeach
    </ul>
</div>
@endif

@if ($user->trusted && !$user->key_holder)
<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Door Key</h3>
            </div>
            <div class="panel-body">
                @if (!$user->profile->profile_photo && !$user->profile->new_profile_photo && !$user->profile->profile_photo_on_wall)
                    <p>
                        If you would like a door key you will need to upload a profile photo so other members can identify you.<br />
                        You can do this from the <a href="{{ route('account.profile.edit', [$user->id]) }}">profile edit page</a>.
                        From that page you can also choose to hide your photo from public view.
                    </p>
                @elseif (!$user->key_deposit_payment_id)
                    <p>If you would like a door key you need to pay a £10 deposit.</p>

                    @include('partials/payment-form', ['reason'=>'door-key', 'displayReason'=>'Door Key Deposit', 'returnPath'=>route('account.show', [$user->id], false), 'amount'=>10, 'buttonLabel'=>'Pay Now', 'methods'=>['gocardless', 'stripe', 'balance']])

                    <small>If you want to pay using cash please find a trustee who can top up your balance.</small>
                @else
                    You have paid the key deposit, please let a trustee know and they will issue you with a key.
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if (!$user->profile->profile_photo && !$user->profile->new_profile_photo && $user->key_holder && !$user->profile->profile_photo_on_wall)
<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Profile photo missing</h3>
            </div>
            <div class="panel-body">
                A requirement of being a key holder is having a profile photo uploaded so other members can identify you.<br />
                You can upload a photo from the <a href="{{ route('account.profile.edit', [$user->id]) }}">profile edit page</a>.
                From that page you can also choose to hide your photo from public view.
            </div>
        </div>
    </div>
</div>
@endif

@if ($user->promoteGoCardless())

    <div class="row">
        <div class="col-xs-12 col-md-10">
            @include('account.partials.switch-to-gocardless-panel')
        </div>
    </div>

@endif

@if ($user->promoteVariableGoCardless())
    <div class="row">
        <div class="col-xs-12 col-md-10">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h4>Want to change your subscription amount?</h4>
                    <ul>
                        <li>Click the button below and fill in Direct Debit form</li>
                        <li>Return to the BBMS</li>
                        <li>Use the "change" link which will be next to your subscription amount (top right)</li>
                    </ul>
                    {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.gocardless-migrate'])) }}
                    {{ Form::submit('Setup a variable Direct Debit', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    <p>
                        <br />
                        <strong>What does this do?</strong>
                        This will move you over to the new direct debit process where you authorise
                        Build Brighton once and it applies to all the other payments.<br />
                        You can still cancel at any point and the same protections as before will apply.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

<!--
<div class="row">
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Build Brighton Credit</h3>
            </div>
            <div class="panel-body">
                <p>
                    There are a number services at Build Brighton that require payments, such as the laser fee,
                    tuck shop and component store. You can maintain a balance here to pay for these services quickly and easily.
                </p>
                <strong>{{ $user->present()->cashBalance }}</strong>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id])) }}
                {{ Form::hidden('reason', 'balance') }}
                {{ Form::hidden('source', 'gocardless') }}
                {{ Form::text('amount', '5.00') }}
                {{ Form::submit('Top up Now (Direct Debit)', array('class'=>'btn btn-primary btn-xs')) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
-->

@if ($user->status == 'setting-up')

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            @include('account.partials.setup-panel')
        </div>
    </div>

@else

    @if ($user->status == 'left')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Member Left</h3>
                </div>
                <div class="panel-body">
                    <p>To rejoin please setup a direct debit for the monthly subscription.</p>
                    @include('account.partials.setup-payment')
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($user->status == 'leaving')
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Member Leaving</h3>
                </div>
                <div class="panel-body">
                    <p class="lead">
                        You're currently setup to leave Build Brighton once your subscription payment expires.<br />
                        Once this happens you will no longer have access to the work space, mailing list or any other member areas.
                    </p>
                    <p>
                        If you wish to rejoin please use the payment options below
                    </p>
                    @include('account.partials.setup-payment')

                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($user->status == 'payment-warning')
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
                @include('account.partials.payment-problem-panel')
            </div>
        </div>
    @endif


    <div class="row">
        <div class="col-xs-12 col-lg-12">
            @include('account.partials.induction-panel')
        </div>
    </div>


    @if ($user->status != 'honorary')
        <div class="row">
            <div class="col-xs-12 col-lg-12 pull-left">
                @include('account.partials.payments-panel')
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-lg-12 pull-left">
                @include('account.partials.sub-charges')
            </div>
        </div>


        @if (($user->status != 'left') && ($user->status != 'leaving'))
        <div class="row">
            <div class="col-xs-12 col-lg-4">
                @include('account.partials.cancel-panel')
            </div>
        </div>
        @endif
    @endif

@endif


@stop