@if (Auth::user()->isAdmin())
    <a href="#adminMemberPanel" data-toggle="collapse" class="btn btn-default btn-sm">Show/Hide Admin Controls</a>
<div class="row well collapse" id="adminMemberPanel">

    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) }}
                <div class="form-group">
                    {{ Form::label('trusted', 'Trusted Member', ['class'=>'col-sm-4 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('trusted', ['0'=>'No', '1'=>'Yes'], $user->trusted, ['class'=>'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) }}
                <div class="form-group">
                    {{ Form::label('key_holder', 'Key Holder', ['class'=>'col-sm-4 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('key_holder', ['0'=>'No', '1'=>'Yes'], $user->key_holder, ['class'=>'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @if ($user->profile->new_profile_photo)
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) }}

                <div class="form-group">
                    {{ Form::label('new_photo', 'New Photo', ['class'=>'col-sm-4 control-label']) }}
                    <div class="col-sm-5">
                        <img src="{{ \BB\Helpers\UserImage::newThumbnailUrl($user->hash) }}" width="100" />
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('photo_approved', 'Photo Approved', ['class'=>'col-sm-4 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('photo_approved', ['0'=>'No', '1'=>'Yes'], 1, ['class'=>'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endif

    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) }}
                <div class="form-group">
                    {{ Form::label('profile_photo_on_wall', 'Profile Photo On Wall', ['class'=>'col-sm-4 control-label']) }}
                    <div class="col-sm-5">
                        {{ Form::select('profile_photo_on_wall', ['0'=>'No', '1'=>'Yes'], $user->profile->profile_photo_on_wall, ['class'=>'form-control']) }}
                    </div>
                    <div class="col-sm-3">
                        {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    @if (!$user->induction_completed)
    <div class="col-xs-12 col-sm-6">
        {{ Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) }}
        <div class="form-group">
            {{ Form::label('induction_completed', 'Induction Completed', ['class'=>'col-sm-4 control-label']) }}
            <div class="col-sm-5">
                {{ Form::select('induction_completed', ['0'=>'No', '1'=>'Yes'], $user->induction_completed, ['class'=>'form-control']) }}
            </div>
            <div class="col-sm-3">
                {{ Form::submit('Update', array('class'=>'btn btn-default')) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
    @endif


    <div class="col-xs-12 col-sm-6">
        @if ($user->keyFob())

        {{ Form::open(array('method'=>'DELETE', 'route' => ['keyfob.destroy', $user->keyFob()->id], 'class'=>'form-horizontal')) }}

            <div class="form-group">
                {{ Form::label('key_fob', 'Key Fob', ['class'=>'col-sm-4 control-label']) }}
                <div class="col-sm-5">
                    <p class="form-control-static">{{ $user->keyFob()->key_id }}</p>
                </div>
                <div class="col-sm-3">
                    {{ Form::submit('Mark Lost', array('class'=>'btn btn-default')) }}
                </div>
            </div>
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::close() }}
        @else
        {{ Form::open(array('method'=>'POST', 'route' => ['keyfob.store'], 'class'=>'form-horizontal')) }}
        <div class="form-group">
            {{ Form::label('key_id', 'Key Fob ID', ['class'=>'col-sm-4 control-label']) }}
            <div class="col-sm-5">
                {{ Form::text('key_id', '', ['class'=>'form-control']) }}
            </div>
            <div class="col-sm-3">
                {{ Form::submit('Add', array('class'=>'btn btn-default')) }}
            </div>
        </div>
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::close() }}
        @endif
    </div>

    <div class="col-xs-12 col-sm-6">

        {{ Form::open(['method'=>'POST', 'route' => ['account.payment.cash.create', $user->id], 'class'=>'form-horizontal']) }}

        <div class="form-group">
            {{ Form::label('key_fob', 'Balance', ['class'=>'col-sm-4 control-label']) }}
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {{ Form::input('number', 'amount', '', ['class'=>'form-control', 'step'=>'0.01', 'required'=>'required']) }}
                </div>
            </div>
            <div class="col-sm-3">
                {{ Form::submit('Credit', array('class'=>'btn btn-primary')) }}
            </div>
        </div>

        {{ Form::hidden('reason', 'balance') }}
        {{ Form::hidden('return_path', 'account/'.$user->id) }}
        {{ Form::close() }}

    </div>

    @if ($user->payment_method == 'cash')
    <div class="col-xs-12 col-sm-6">
        {{ Form::open(array('method'=>'POST', 'class'=>'form-horizontal', 'route' => ['account.payment.store', $user->id])) }}

        <div class="form-group">
            <div class="col-sm-5"></div>
            <div class="col-sm-3">
                {{ Form::submit('Record A &pound;'.round($user->monthly_subscription).' Cash Subscription Payment', array('class'=>'btn btn-default')) }}
            </div>
        </div>

        {{ Form::hidden('reason', 'subscription') }}
        {{ Form::hidden('source', 'cash') }}
        {{ Form::close() }}
        </div>
    @endif

</div>
@endif