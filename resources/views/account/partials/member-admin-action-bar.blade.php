@if (Auth::user()->isAdmin())
    
<div class="row well">

    <div class="col-xs-12 col-sm-6">
        <div class="row">
            <div class="col-xs-12">
                <h4>Trusted Member</h4>
                <p>The member will be automatically emailed about being made trusted but not loosing trusted status.</p>
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) !!}
                <div class="form-group">
                    <div class="col-sm-5">
                    @if ($user->trusted)
                        {!! Form::hidden('trusted', 0) !!}
                        {!! Form::submit('Remove Trusted Status', array('class'=>'btn btn-default')) !!}
                    @else
                        {!! Form::hidden('trusted', 1) !!}
                        {!! Form::submit('Make a trusted member', array('class'=>'btn btn-default')) !!}
                    @endif
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        @if ($user->trusted && $user->key_deposit_payment_id)
        <div class="row">
            <div class="col-xs-12">
                <h4>Key Holder</h4>
                <p>
                    This controls their access to the space through the key fobs.
                </p>
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal js-quick-update')) !!}
                <div class="form-group">
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
                <h4>New photo to approve</h4>
                <p>If rejected they will be emailed explaining the photo wasn't suitable.</p>
                {!! Form::open(array('method'=>'PUT', 'route' => ['account.admin-update', $user->id], 'class'=>'form-horizontal')) !!}

                <div class="form-group">
                    <div class="col-sm-5">
                        <img src="{{ \BB\Helpers\UserImage::newThumbnailUrl($user->hash) }}" width="100" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-5">
                        {!! Form::select('photo_approved', ['0'=>'Rejected', '1'=>'Approved'], 1, ['class'=>'form-control']) !!}
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
        <h4>Key Fob</h4>
        <p>This is the ID number associated with their RFID tag. They don't need to be a key holder to get an RFID tag</p>
        @if ($user->keyFob())
        {!! Form::open(array('method'=>'DELETE', 'route' => ['keyfob.destroy', $user->keyFob()->id], 'class'=>'form-horizontal')) !!}
            <div class="form-group">
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

        <h4>Balance Top up</h4>
        <p>Use this if the member has given you some cash to top up their balance.</p>

        {!! Form::open(['method'=>'POST', 'route' => ['account.payment.cash.create', $user->id], 'class'=>'form-horizontal']) !!}

        <div class="form-group">
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

        <h4>Withdraw</h4>
        <p>This will remove money from their balance, its used if your giving them cash.</p>

        {!! Form::open(['method'=>'DELETE', 'route' => ['account.payment.cash.destroy', $user->id], 'class'=>'form-horizontal']) !!}

        <div class="form-group">
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

    @if ($user->status == 'setting-up')
        <div class="col-xs-12 col-sm-6">
            <div class="row">
                <div class="col-xs-12">
                    <h4>Setup</h4>
                    <p>Activate this members subscription but have them pay using their balance</p>
                    {!! Form::open(array('method'=>'POST', 'class'=>'form-horizontal', 'route' => ['account.update-sub-method', $user->id])) !!}
                    <div class="form-group">
                        <div class="col-sm-5">
                            @if ($user->cash_balance > ($user->monthly_subscription * 100))
                            {!! Form::hidden('payment_method', 'balance') !!}
                            {!! Form::submit('Activate & pay by balance', array('class'=>'btn btn-default')) !!}
                            @else
                                <p>The user doesn't have enough money in their balance</p>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif

    @if ($user->status == 'setting-up')
        <div class="col-xs-12 col-sm-6">
            <div class="row">
                <div class="col-xs-12">
                    <h4>Delete</h4>
                    <p>Is this an old record? No sign of {{ $user->name }}?</p>
                    {!! Form::open(array('method'=>'DELETE', 'class'=>'form-horizontal', 'route' => ['account.destroy', $user->id])) !!}
                    <div class="form-group">
                        <div class="col-sm-5">
                            {!! Form::submit('Delete this member', array('class'=>'btn btn-default')) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    @endif

</div>

@endif
