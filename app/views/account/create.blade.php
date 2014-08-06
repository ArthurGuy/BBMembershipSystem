<div class="page-header">
    <h1>Join Build Brighton <small>Complete the member registration form to get started</small></h1>
    <p>
        Please fill out the form below, you will then be asked to setup a direct debit.<br />
        You need to provide us with your real name, this is <a href="http://www.legislation.gov.uk/ukpga/2006/46/part/8/chapter/2/crossheading/general" target="_blank">required by UK law</a>
    </p>
</div>

{{ Form::open(array('route' => 'account.store')) }}

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="form-group {{ $errors->has('given_name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('given_name', 'First Name') }}
            {{ Form::text('given_name', null, ['class'=>'form-control']) }}
            {{ $errors->first('given_name', '<span class="help-block">:message</span>') }}
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="form-group {{ $errors->has('family_name') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('family_name', 'Family Name') }}
            {{ Form::text('family_name', null, ['class'=>'form-control']) }}
            {{ $errors->first('family_name', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', null, ['class'=>'form-control']) }}
            {{ $errors->first('email', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class'=>'form-control']) }}
            {{ $errors->first('password', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('monthly_subscription') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('monthly_subscription', 'Monthly Subscription Amount') }}
            {{ Form::text('monthly_subscription', 20, ['class'=>'form-control', 'placeholder'=>'20']) }}
            {{ $errors->first('monthly_subscription', '<span class="help-block">:message</span>') }}
            <span class="help-block">How much do you want to contribute each month, the average is around £20</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('address_line_1') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('address_line_1', 'Address Line 1') }}
            {{ Form::text('address_line_1', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_1', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('address_line_2') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('address_line_2', 'Address Line 2') }}
            {{ Form::text('address_line_2', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_2', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('address_line_3') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('address_line_3', 'Address Line 3') }}
            {{ Form::text('address_line_3', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_3', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('address_line_4') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('address_line_4', 'Address Line 4') }}
            {{ Form::text('address_line_4', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_line_4', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('address_postcode') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('address_postcode', 'Post Code') }}
            {{ Form::text('address_postcode', null, ['class'=>'form-control']) }}
            {{ $errors->first('address_postcode', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ $errors->has('emergency_contact') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('emergency_contact', 'Emergency Contact') }}
            {{ Form::text('emergency_contact', null, ['class'=>'form-control']) }}
            {{ $errors->first('emergency_contact', '<span class="help-block">:message</span>') }}
            <span class="help-block">Please give us the name and contact details of someone we can contact if needed</span>
        </div>
    </div>
</div>

{{ Form::submit('Join', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}