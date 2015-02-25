@if (Auth::user()->isAdmin())
<div class="row well">

    <div class="col-xs-6 col-md-6">
        <h4>Door Key</h4>
        @if (!$user->trusted)
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::label('trusted', 'Trusted Member') }}
                    {{ Form::select('trusted', ['0'=>'No', '1'=>'Yes'], $user->trusted, ['class'=>'form-control']) }}
                </div>
                {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
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
                {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </div>
        </div>
        @elseif ($user->trusted && !$user->key_deposit_payment_id)
        <div class="row">
            <div class="col-xs-12">
                <p>A deposit needs to be paid before a key can be issued. Get the member to make this payment themselves.</p>
            </div>
        </div>
        @endif
    </div>

    @if ($user->profile->new_profile_photo)
    <div class="col-xs-12 col-md-6">
        <h4>Review Profile Photo</h4>
        <img src="{{ \BB\Helpers\UserImage::newThumbnailUrl($user->hash) }}" />
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::label('photo_approved', 'Approved') }}
                    {{ Form::select('photo_approved', ['0'=>'No', '1'=>'Yes'], 1, ['class'=>'form-control']) }}
                </div>
                {{ Form::submit('Save', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endif

    <div class="col-xs-12 col-md-6">
        <h4>Profile Photo On Wall</h4>
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::label('profile_photo_on_wall', 'On Wall') }}
                    {{ Form::select('profile_photo_on_wall', ['0'=>'No', '1'=>'Yes'], $user->profile->profile_photo_on_wall, ['class'=>'form-control']) }}
                </div>
                {{ Form::submit('Save', array('class'=>'btn btn-default')) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @if (!$user->induction_completed)
    <div class="col-xs-12 col-md-6">
        <h4>Induction</h4>
        {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'navbar-form navbar-left')) }}
        <div class="form-group">
            {{ Form::label('induction_completed', 'Induction Completed') }}
            {{ Form::select('induction_completed', ['0'=>'No', '1'=>'Yes'], $user->induction_completed, ['class'=>'form-control']) }}
        </div>
        {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
    </div>
    @endif


    <div class="col-xs-12 col-md-6">
        <h4>Key Fob</h4>
        @if ($user->keyFob())
        <span class="navbar-text">{{ $user->keyFob()->key_id }}</span>
        {{ Form::open(array('method'=>'DELETE', 'route' => ['keyfob.destroy', $user->keyFob()->id], 'class'=>'navbar-form navbar-left')) }}
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::submit('Mark Lost', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        @else
        {{ Form::open(array('method'=>'POST', 'route' => ['keyfob.store'], 'class'=>'navbar-form navbar-left')) }}
        <div class="form-group">
            {{ Form::label('trusted', 'Key Fob ID') }}
            {{ Form::text('key_id', '', ['class'=>'form-control']) }}
        </div>
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::submit('Add', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        @endif
    </div>

    <div class="col-xs-12 col-md-6">

        <h4>Record a cash balance payment</h4>
        {{ Form::open(['method'=>'POST', 'route' => ['account.payment.cash.create', $user->id], 'class'=>'form-inline']) }}
        {{ Form::hidden('reason', 'balance') }}
        {{ Form::hidden('return_path', 'account/'.$user->id) }}

        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {{ Form::input('number', 'amount', '', ['class'=>'form-control', 'step'=>'0.01', 'required'=>'required']) }}
            </div>
        </div>

        {{ Form::submit('Record Payment', array('class'=>'btn btn-primary')) }}

        {{ Form::close() }}

    </div>

    @if ($user->payment_method == 'cash')
    <div class="col-xs-12 col-md-6">
        <h4>Cash Subscription Payment</h4>
        {{ Form::open(array('method'=>'POST', 'class'=>'form-inline', 'route' => ['account.payment.store', $user->id])) }}
        {{ Form::hidden('reason', 'subscription') }}
        {{ Form::hidden('source', 'cash') }}
        {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Cash Payment', array('class'=>'btn btn-default')) }}
        {{ Form::close() }}
        </div>
    @endif

</div>
@endif