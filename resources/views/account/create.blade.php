@extends('layouts.main')

@section('meta-title')
Join Build Brighton
@stop

@section('content')

<div class="register-container col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">

    <div class="row">
        <div class="col-xs-12">
            <div class="page-header">
                <h1>Apply to Join Build Brighton</h1>
                <p> Build Brighton is a fantastic space and community of like minded people.We are accepting applications for membership, but before applying to join, please come along to an open evening, to make sure the space meets your needs and to find out more. </p>
            </div>
        </div>
    </div>

    {!! Form::open(array('route' => 'account.store', 'class'=>'form-horizontal', 'files'=>true)) !!}
    <div class="row">
        <div class="col-xs-12">
            <p>
                Please fill out the form below, on the next page you will be asked to setup a direct debit for the monthly payment.<br />
                We need your real name and address, this is <a href="http://www.legislation.gov.uk/ukpga/2006/46/part/8/chapter/2/crossheading/general" target="_blank">required by UK law</a><br />
                Your address will be kept private but your name will be listed publicly as being a member of our community
            </p>
        </div>
    </div>

    @if (Notification::hasMessage())
    <div class="alert alert-{{ Notification::getLevel() }} alert-dismissable">
        {!! Notification::getMessage() !!}
    </div>
    @endif


    <div class="form-group {{ Notification::hasErrorDetail('given_name', 'has-error has-feedback') }}">
        {!! Form::label('given_name', 'First Name', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('given_name', null, ['class'=>'form-control', 'autocomplete'=>'given-name', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('given_name') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('family_name', 'has-error has-feedback') }}">
        {!! Form::label('family_name', 'Family Name', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('family_name', null, ['class'=>'form-control', 'autocomplete'=>'family-name', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('family_name') !!}
        </div>
    </div>
    <div class="form-group {{ Notification::hasErrorDetail('email', 'has-error has-feedback') }}">
        {!! Form::label('email', 'Email', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::input('email', 'email', null, ['class'=>'form-control', 'autocomplete'=>'email', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('email') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('password', 'has-error has-feedback') }}">
        {!! Form::label('password', 'Password', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::password('password', ['class'=>'form-control', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('password') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('monthly_subscription', 'has-error has-feedback') }}">
        {!! Form::label('monthly_subscription', 'Monthly Subscription Amount', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {!! Form::input('number', 'monthly_subscription', 25, ['class'=>'form-control', 'placeholder'=>'20', 'min'=>'5', 'step'=>'1']) !!}
            </div>
            {!! Notification::getErrorDetail('monthly_subscription') !!}
            <span class="help-block"><button type="button" class="btn btn-link" data-toggle="modal" data-target="#howMuchShouldIPayModal">How much should I pay?</button></span>
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('visited_space', 'has-error has-feedback') }}">
        <div class="col-sm-9 col-lg-7 col-sm-offset-3">
            <span class="help-block">
                Build Brighton may not be the type of space you expect so before joining you need to have come
                along to one of our Thursday open evnings first
            </span>
            {!! Form::checkbox('visited_space', true, null, ['class'=>'']) !!}
            {!! Form::label('visited_space', 'I have visited Build Brighton', ['class'=>'']) !!}
            {!! Notification::getErrorDetail('visited_space') !!}
        </div>
    </div>


    <div class="form-group {{ Notification::hasErrorDetail('address.line_1', 'has-error has-feedback') }}">
        {!! Form::label('address[line_1]', 'Address Line 1', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('address[line_1]', null, ['class'=>'form-control', 'autocomplete'=>'address-line1', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('address.line_1') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('address.line_2', 'has-error has-feedback') }}">
        {!! Form::label('address[line_2]', 'Address Line 2', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('address[line_2]', null, ['class'=>'form-control', 'autocomplete'=>'address-line2']) !!}
            {!! Notification::getErrorDetail('address.line_2') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('address.line_3', 'has-error has-feedback') }}">
        {!! Form::label('address[line_3]', 'Address Line 3', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('address[line_3]', null, ['class'=>'form-control', 'autocomplete'=>'address-level2']) !!}
            {!! Notification::getErrorDetail('address.line_3') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('address.line_4', 'has-error has-feedback') }}">
        {!! Form::label('address[line_4]', 'Address Line 4', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('address[line_4]', null, ['class'=>'form-control', 'autocomplete'=>'address-level1']) !!}
            {!! Notification::getErrorDetail('address.line_4') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('address.postcode', 'has-error has-feedback') }}">
        {!! Form::label('address[postcode]', 'Post Code', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('address[postcode]', null, ['class'=>'form-control', 'autocomplete'=>'postal-code', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('address.postcode') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('phone', 'has-error has-feedback') }}">
        {!! Form::label('phone', 'Phone', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::input('tel', 'phone', null, ['class'=>'form-control', 'autocomplete'=>'tel', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('phone') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('emergency_contact', 'has-error has-feedback') }}">
        {!! Form::label('emergency_contact', 'Emergency Contact', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('emergency_contact', null, ['class'=>'form-control', 'required' => 'required']) !!}
            {!! Notification::getErrorDetail('emergency_contact') !!}
            <span class="help-block">Please give us the name and contact details of someone we can contact if needed.</span>
        </div>
    </div>

    <div class="row">
        <p class="col-sm-9 col-lg-9 col-sm-offset-1">
            Build Brighton is a community of people rather than a company and it operates largely on trust.<br />
            We require a profile photo as it helps members to recognise new faces.
        </p>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('new_profile_photo', 'has-error has-feedback') }}">
        {!! Form::label('new_profile_photo', 'Profile Photo', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::file('new_profile_photo', null, ['class'=>'form-control']) !!}
            {!! Notification::getErrorDetail('new_profile_photo') !!}
            <span class="help-block"><strong>This must be a clear image of your face</strong>, (passport photo style) its not much use for identification otherwise!</span>
            <span class="help-block">This photo will be displayed to members and may be used within the space, it will also be listed publicly on this site but you can turn that off below if you want.</span>
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('profile_photo_private', 'has-error has-feedback') }}">
        <div class="col-sm-9 col-lg-7 col-sm-offset-3">
            {!! Form::checkbox('profile_photo_private', true, null, ['class'=>'']) !!}
            {!! Form::label('profile_photo_private', 'Make my photo private', ['class'=>'']) !!}
            {!! Notification::getErrorDetail('profile_photo_private') !!}
            <span class="help-block">If you want to block your photo from displaying outside Build Brighton please check this box although we would rather you didn't.</span>
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('rules_agreed', 'has-error has-feedback') }}">
        <div class="col-sm-9 col-lg-7 col-sm-offset-3">
            <span class="help-block">Please read the <a href="https://bbms.buildbrighton.com/resources/policy/rules" target="_blank">rules</a> and click the checkbox to confirm you agree to them</span>
            {!! Form::checkbox('rules_agreed', true, null, ['class'=>'']) !!}
            {!! Form::label('rules_agreed', 'I agree to the Build Brighton rules', ['class'=>'']) !!}
            {!! Notification::getErrorDetail('rules_agreed') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {!! Form::submit('Join', array('class'=>'btn btn-primary')) !!}
        </div>
    </div>


    {!! Form::close() !!}

</div>

<div class="modal fade" id="howMuchShouldIPayModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Subscription Suggestions</h4>
            </div>
            <div class="modal-body">
                <p>If you're not sure how much to pay, here are some general guidelines to help you find a suitable subscription amount for your circumstances:</p>

                &pound;5 - &pound;25 a month:
                <ul>
                    <li>You are on a low income and unable to afford a higher amount, you will need to contact us and provide some more details.</li>
                </ul>

                &pound;25 - &pound;35 a month:
                <ul>
                    <li>You are planning to visit the makerspace regularly and are a professional / in full-time employment</li>
                </ul>

                &pound;35 a month and up:
                <ul>
                    <li>You are planning to visit the makerspace regularly and would like to provide a little extra support (thank you!)</li>
                </ul>

                <p>
                    If you feel that the makerspace is worth more to you then please do adjust your subscription accordingly.
                    You can also change your subscription amount at any time!
                </p>

                <p>
                    If you can only pay less than &pound;25 a month please select an amount over £25 and complete
                    this form, on the next page you will be asked to setup a subscription payment.
                    Before you do this please send the trustees an email letting them know how much you would like to
                    pay, they will then override the amount so you can continue to setup a subscription.
                </p>
            </div>
        </div>
    </div>
</div>

@stop
