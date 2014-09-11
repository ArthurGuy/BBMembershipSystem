<div class="row page-header">
    <div class="col-xs-12 col-sm-10">
        <h1>
            @if ($user->profile_photo)
            <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" width="100" height="100" />
            @else
            <img src="{{ \BB\Helpers\UserImage::anonymous() }}" width="100" height="100" />
            @endif
            {{ $user->name }} <small>{{ $user->email }}</small>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-2">
        <p><a href="{{ route('account.edit', $user->id) }}" class="btn btn-info btn-sm">Edit Your Details</a></p>
    </div>
</div>
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
        <p class="navbar-text">Subscription Expires: {{ $user->subscription_expires->toFormattedDateString() }}</p>
    </li>
    @endif
</ul>

@if (Auth::user()->isAdmin())
<div class="row well">

    <div class="col-xs-6 col-md-6">
        @if (!$user->trusted)
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::label('trusted', 'Trusted Member') }}
                    {{ Form::select('trusted', ['0'=>'No', '1'=>'Yes'], $user->trusted, ['class'=>'form-control']) }}
                </div>
                {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}
            </div>
        </div>
        @endif
        @if ($user->trusted && $user->key_deposit_payment_id)
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::label('key_holder', 'Key Holder') }}
                    {{ Form::select('key_holder', ['0'=>'No', '1'=>'Yes'], $user->key_holder, ['class'=>'form-control']) }}
                </div>
                {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
                {{ Form::close() }}
            </div>
        </div>
        @elseif ($user->trusted && !$user->key_deposit_payment_id)
        <div class="row">
            <div class="col-xs-12">
                <p>A deposit needs to be paid before a key can be issued</p>
                {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.store', $user->id], 'class'=>'')) }}
                {{ Form::hidden('reason', 'door-key') }}
                {{ Form::hidden('source', 'manual') }}
                {{ Form::submit('Key Deposit Paid', array('class'=>'btn btn-default btn-xs')) }}
                {{ Form::close() }}
            </div>
        </div>
        @endif
    </div>
    @if (!$user->induction_completed)
        <div class="col-xs-12 col-md-6">
            {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
            <div class="form-group">
                {{ Form::label('induction_completed', 'Induction Completed') }}
                {{ Form::select('induction_completed', ['0'=>'No', '1'=>'Yes'], $user->induction_completed, ['class'=>'form-control']) }}
            </div>
            {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    @endif


    <div class="col-xs-12 col-md-6">
        @if ($user->keyFob())
            <span class="navbar-text">{{ $user->keyFob()->key_id }}</span>
            {{ Form::open(array('method'=>'DELETE', 'route' => ['keyfob.destroy', $user->keyFob()->id], 'class'=>'navbar-form navbar-left')) }}
            {{ Form::hidden('user_id', $user->id) }}
            {{ Form::submit('Mark Lost', array('class'=>'btn btn-primary')) }}
            {{ Form::close() }}
        @else
        {{ Form::open(array('method'=>'POST', 'route' => ['keyfob.store'], 'class'=>'navbar-form navbar-left')) }}
        <div class="form-group">
            {{ Form::label('trusted', 'Key Fob ID') }}
            {{ Form::text('key_id', '', ['class'=>'form-control']) }}
        </div>
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::submit('Add', array('class'=>'btn btn-primary')) }}
        {{ Form::close() }}
        @endif
    </div>
</div>
@endif

@if ($user->trusted && !$user->key_holder)
<div class="row">
    <div class="col-xs-12 col-md-6 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Door Key</h3>
            </div>
            <div class="panel-body">
                @if (!$user->key_deposit_payment_id)
                    <p>If you would like a door key you need to pay a £10 deposit, this can be paid by a one off direct debit payment or by cash at the space.</p>
                    <p>
                    {{ Form::open(array('method'=>'POST', 'route' => ['account.payment.create', $user->id])) }}
                    {{ Form::hidden('reason', 'door-key') }}
                    {{ Form::hidden('source', 'gocardless') }}
                    {{ Form::submit('Pay Now (DD)', array('class'=>'btn btn-primary btn-xs')) }}
                        <small>You don't need to be paying via direct debit to use the option</small>
                    {{ Form::close() }}
                    </p>
                @else
                    You have paid the key deposit, please let a trustee know and they will issue you will a key.
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@if ($user->promoteGoCardless())

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 pull-left">
            @include('account.partials.switch-to-gocardless-panel')
        </div>
    </div>

@endif




@if ($user->status == 'setting-up')

    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3 pull-left">
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
                    <p>To rejoin please setup a direct debit payment for the monthly subscription.</p>
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
                        Your currently setup to leave Build Brighton once your subscription payment expires.<br />
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


        @if (($user->status != 'left') && ($user->status != 'leaving'))
        <div class="row">
            <div class="col-xs-12 col-lg-4">
                @include('account.partials.cancel-panel')
            </div>
        </div>
        @endif
    @endif

@endif


