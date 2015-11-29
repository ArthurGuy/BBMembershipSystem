@if (Auth::user()->isAdmin())
    
<div class="row well">

    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) !!}
                <div class="form-group">
                    {!! Form::label('trusted', 'Trusted Member', ['class'=>'col-sm-4 control-label']) !!}
                @if ($user->trusted)
                    {!! Form::hidden('trusted', 0) !!}
                    {!! Form::submit('Remove Trusted Status', array('class'=>'btn btn-default')) !!}
                @else
                    {!! Form::hidden('trusted', 1) !!}
                    {!! Form::submit('Make Trusted', array('class'=>'btn btn-default')) !!}
                @endif
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        @if ($user->trusted && $user->key_deposit_payment_id)
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal js-quick-update')) !!}
                <div class="form-group">
                    {!! Form::label('key_holder', 'Key Holder', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('key_holder', ['0'=>'No', '1'=>'Yes'], $user->key_holder, ['class'=>'form-control']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @endif
    </div>

    @if ($user->profile->new_profile_photo)
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) !!}

                <div class="form-group">
                    {!! Form::label('new_photo', 'New Photo', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-5">
                        <img src="{{ \BB\Helpers\UserImage::newThumbnailUrl($user->hash) }}" width="100" />
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('photo_approved', 'Photo Approved', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-5">
                        {!! Form::select('photo_approved', ['0'=>'No', '1'=>'Yes'], 1, ['class'=>'form-control']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::submit('Update', array('class'=>'btn btn-default')) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endif

    <!--
    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal js-quick-update')) !!}
                <div class="form-group">
                    {!! Form::label('profile_photo_on_wall', 'Profile Photo On Wall', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('profile_photo_on_wall', ['0'=>'No', '1'=>'Yes'], $user->profile->profile_photo_on_wall, ['class'=>'form-control']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    -->


    <div class="col-xs-12 col-sm-6">
        @if ($user->keyFob())

        {!! Form::open(array('method'=>'DELETE', 'route' => ['keyfob.destroy', $user->keyFob()->id], 'class'=>'form-horizontal')) !!}

            <div class="form-group">
                {!! Form::label('key_fob', 'Key Fob', ['class'=>'col-sm-4 control-label']) !!}
                <div class="col-sm-5">
                    <p class="form-control-static">{{ $user->keyFob()->key_id }}</p>
                </div>
                <div class="col-sm-3">
                    {!! Form::submit('Mark Lost', array('class'=>'btn btn-default')) !!}
                </div>
            </div>
        {!! Form::hidden('user_id', $user->id) !!}
        {!! Form::close() !!}
        @else
        {!! Form::open(array('method'=>'POST', 'route' => ['keyfob.store'], 'class'=>'form-horizontal')) !!}
        <div class="form-group">
            {!! Form::label('key_id', 'Key Fob ID', ['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-5">
                {!! Form::text('key_id', '', ['class'=>'form-control']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::submit('Add', array('class'=>'btn btn-default')) !!}
            </div>
        </div>
        {!! Form::hidden('user_id', $user->id) !!}
        {!! Form::close() !!}
        @endif
    </div>

    <div class="col-xs-12 col-sm-6">

        {!! Form::open(['method'=>'POST', 'route' => ['account.payment.cash.create', $user->id], 'class'=>'form-horizontal']) !!}

        <div class="form-group">
            {!! Form::label('number', 'Balance Cash Top up', ['class'=>'col-sm-4 control-label']) !!}
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::input('number', 'amount', '', ['class'=>'form-control', 'step'=>'0.01', 'required'=>'required']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                {!! Form::submit('Add Credit', array('class'=>'btn btn-primary')) !!}
            </div>
        </div>

        {!! Form::hidden('reason', 'balance') !!}
        {!! Form::hidden('source_id', 'user:' . \Auth::id()) !!}
        {!! Form::hidden('return_path', 'account/'.$user->id) !!}
        {!! Form::close() !!}

    </div>

    <div class="col-xs-12 col-sm-6">

        {!! Form::open(['method'=>'DELETE', 'route' => ['account.payment.cash.destroy', $user->id], 'class'=>'form-horizontal']) !!}

        <div class="form-group">
            {!! Form::label('number', 'Withdraw', ['class'=>'col-sm-3 control-label']) !!}
            <div class="col-sm-3">
                <div class="input-group">
                    <div class="input-group-addon">&pound;</div>
                    {!! Form::input('number', 'amount', '', ['class'=>'form-control', 'step'=>'0.01', 'required'=>'required']) !!}
                </div>
            </div>
            <div class="col-sm-3">
                {!! Form::select('ref', ['cash'=>'Cash', 'bank-transfer'=>'Bank Transfer'], null, ['class'=>'form-control']) !!}
            </div>
            <div class="col-sm-3">
                {!! Form::submit('Remove Credit', array('class'=>'btn btn-primary')) !!}
            </div>
        </div>

        {!! Form::hidden('return_path', 'account/'.$user->id) !!}
        {!! Form::close() !!}

    </div>

    @if ($user->payment_method == 'cash')
    <div class="col-xs-12 col-sm-6">
        {!! Form::open(array('method'=>'POST', 'class'=>'form-horizontal', 'route' => ['account.payment.store', $user->id])) !!}

        <div class="form-group">
            <div class="col-sm-5"></div>
            <div class="col-sm-3">
                {!! Form::submit('Record A &pound;'.round($user->monthly_subscription).' Cash Subscription Payment', array('class'=>'btn btn-default')) !!}
            </div>
        </div>

        {!! Form::hidden('reason', 'subscription') !!}
        {!! Form::hidden('source', 'cash') !!}
        {!! Form::close() !!}
        </div>
    @endif

    @if ($newAddress)
        <div class="col-xs-12 col-sm-6">
            <div class="row">
                <div class="col-xs-12">
                    {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) !!}

                    <div class="form-group">
                        {!! Form::label('approve_new_address', 'New Address', ['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-5">
                            {{ $newAddress->line_1 }}<br />
                            {{ $newAddress->line_2 }}<br />
                            {{ $newAddress->line_3 }}<br />
                            {{ $newAddress->line_4 }}<br />
                            {{ $newAddress->postcode }}
                        </div>
                        <div class="col-sm-3">
                            {!! Form::submit('Approve', array('class'=>'btn btn-default', 'name'=>'approve_new_address')) !!}
                            {!! Form::submit('Decline', array('class'=>'btn btn-default', 'name'=>'approve_new_address')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif

</div>

@endif