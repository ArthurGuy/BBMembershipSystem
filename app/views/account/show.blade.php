<div class="row">
    <div class="page-header col-xs-12 col-sm-10">
        <h1>{{ $user->name }} <small>{{ $user->email }}</small></h1>
    </div>
    <div class="col-xs-12 col-sm-2">
        <p><a href="{{ route('account.edit', $user->id) }}" class="btn btn-info btn-sm">Edit Your Details</a></p>
    </div>
</div>


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
        <div class="col-xs-12 col-md-6 col-lg-4 pull-right">
            @include('account.partials.member-status-panel')
        </div>

        <div class="col-xs-12 col-lg-8">
            @include('account.partials.induction-panel')
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-lg-8 pull-left">
            @include('account.partials.payments-panel')
        </div>
    </div>



    @if ($user->status != 'left')
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            @include('account.partials.cancel-panel')
        </div>
    </div>
    @endif


@endif


