<div class="register-container col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <h1>Join Build Brighton</h1>
                <p>
                    Build Brighton is a fantastic space and community of like minded people.<br />
                    If you haven't visited one of our open nights yet then it is highly recommended that you do before signing up.
                </p>
            </div>
        </div>
    </div>

    {{ Form::open(array('route' => 'account.store', 'class'=>'form-horizontal', 'files'=>true)) }}
    <div class="row">
        <div class="col-xs-12">
            <p>
                Please fill out the form below, you will then be asked to setup a direct debit for the monthly payment.<br />
                We need your real name and address, this is <a href="http://www.legislation.gov.uk/ukpga/2006/46/part/8/chapter/2/crossheading/general" target="_blank">required by UK law</a><br />
                Your address will be kept private but your name will be listed publicly as being a member of our community
            </p>
        </div>
    </div>

    @if ($errors->count() > 0)
    <div class="alert alert-danger">
        Something wasn't right, please take a look at the errors below
    </div>
    @endif

    <div class="form-group {{ $errors->has('given_name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('given_name', 'First Name', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('given_name', null, ['class'=>'form-control']) }}
            {{ $errors->first('given_name', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('family_name') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('family_name', 'Family Name', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('family_name', null, ['class'=>'form-control']) }}
            {{ $errors->first('family_name', '<span class="help-block">:message</span>') }}
        </div>
    </div>


    <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('email', 'Email', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::input('email', 'email', null, ['class'=>'form-control']) }}
            {{ $errors->first('email', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('password', 'Password', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::password('password', ['class'=>'form-control']) }}
            {{ $errors->first('password', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('monthly_subscription') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('monthly_subscription', 'Monthly Subscription Amount', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {{ Form::input('number', 'monthly_subscription', 20, ['class'=>'form-control', 'placeholder'=>'20', 'min'=>'5', 'step'=>'1']) }}
            </div>
            {{ $errors->first('monthly_subscription', '<span class="help-block">:message</span>') }}
            <span class="help-block">How much do you want to contribute each month? We operate on a pay-what-you-can basis, most members pay between &pound;10 and &pound;30, the minimum is £5</span>
        </div>
    </div>


    <div class="form-group {{ $errors->has('address_line_1') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('address_line_1', 'Address Line 1', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('address_line_1', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_1', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('address_line_2') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('address_line_2', 'Address Line 2', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('address_line_2', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_2', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('address_line_3') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('address_line_3', 'Address Line 3', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('address_line_3', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_3', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('address_line_4') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('address_line_4', 'Address Line 4', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('address_line_4', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_4', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('address_postcode') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('address_postcode', 'Post Code', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('address_postcode', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_postcode', '<span class="help-block">:message</span>') }}
        </div>
    </div>

    <div class="form-group {{ $errors->has('emergency_contact') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('emergency_contact', 'Emergency Contact', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('emergency_contact', null, ['class'=>'form-control']) }}
            {{ $errors->first('emergency_contact', '<span class="help-block">:message</span>') }}
            <span class="help-block">Please give us the name and contact details of someone we can contact if needed.</span>
        </div>
    </div>

    <div class="row">
        <p class="col-sm-9 col-lg-9 col-sm-offset-1">
            Build Brighton is a community of people rather than a company and it operates largely on trust.<br />
            We require a profile photo as it helps members to recognise new faces.
        </p>
    </div>

    <div class="form-group {{ $errors->has('profile_photo') ? 'has-error has-feedback' : '' }}">
        {{ Form::label('profile_photo', 'Profile Photo', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::file('profile_photo', null, ['class'=>'form-control']) }}
            {{ $errors->first('profile_photo', '<span class="help-block">:message</span>') }}
            <span class="help-block">This must be a clear image of your face, its not much use for identification otherwise!</span>
            <span class="help-block">This photo will be displayed to members and may be used within the space, it will also be listed publicly on this site but you can turn that off below if you want.</span>
        </div>
    </div>

    <div class="form-group {{ $errors->has('profile_photo_private') ? 'has-error has-feedback' : '' }}">
        <div class="col-sm-9 col-lg-7 col-sm-offset-3">
            {{ Form::checkbox('profile_photo_private', true, null, ['class'=>'']) }}
            {{ Form::label('profile_photo_private', 'Make my photo private', ['class'=>'']) }}
            {{ $errors->first('profile_photo_private', '<span class="help-block">:message</span>') }}
            <span class="help-block">If you want to block your photo from displaying outside Build Brighton please check this box although we would rather you didn't.</span>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {{ Form::submit('Join', array('class'=>'btn btn-primary')) }}
        </div>
    </div>


    {{ Form::close() }}

</div>