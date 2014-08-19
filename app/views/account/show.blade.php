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
        <p class="navbar-text">Payment Method: {{ $user->payment_method }}</p>
    </li>
    <li>
        <p class="navbar-text">Monthly Payment: &pound;{{ round($user->monthly_subscription) }}</p>
    </li>
    <li>
        <p class="navbar-text">Subscription Expires: {{ $user->subscription_expires->toFormattedDateString() }}</p>
    </li>
</ul>

@if (Auth::user()->isAdmin())
<div class="row">
    <div class="col-xs-12 col-md-8">
        {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
        <div class="form-group">
            {{ Form::label('trusted', 'Trusted Member') }}
            {{ Form::select('trusted', ['0'=>'No', '1'=>'Yes'], $user->trusted, ['class'=>'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('key_holder', 'Key Holder') }}
            {{ Form::select('key_holder', ['0'=>'No', '1'=>'Yes'], $user->key_holder, ['class'=>'form-control']) }}
        </div>
        <div class="form-group">
            {{ Form::label('induction_completed', 'Induction Completed') }}
            {{ Form::select('induction_completed', ['0'=>'No', '1'=>'Yes'], $user->induction_completed, ['class'=>'form-control']) }}
        </div>
        {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
        {{ Form::close() }}
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
        <div class="col-xs-12 col-md-6 col-md-offset-3 pull-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User Left</h3>
                </div>
                <div class="panel-body">
                    To rejoin please email us
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($user->status == 'payment-warning')
        <div class="row">
            <div class="col-xs-12 col-md-6 col-md-offset-3 pull-left">
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


