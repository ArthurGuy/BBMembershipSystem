<div class="page-header">
    <h1>Edit your details</h1>
</div>

{{ Form::model($user, array('route' => ['account.update', $user->id], 'method'=>'PUT', 'files'=>true)) }}

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="form-group {{ Notification::hasErrorDetail('given_name', 'has-error has-feedback') }}">
            {{ Form::label('given_name', 'First Name') }}
            {{ Form::text('given_name', null, ['class'=>'form-control', 'x-autocompletetype'=>'given-name']) }}
            {{ Notification::getErrorDetail('given_name') }}
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="form-group {{ Notification::hasErrorDetail('family_name', 'has-error has-feedback') }}">
            {{ Form::label('family_name', 'Family Name') }}
            {{ Form::text('family_name', null, ['class'=>'form-control', 'x-autocompletetype'=>'surname']) }}
            {{ Notification::getErrorDetail('family_name') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('email', 'has-error has-feedback') }}">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', null, ['class'=>'form-control', 'x-autocompletetype'=>'email']) }}
            {{ Notification::getErrorDetail('email') }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('secondary_email', 'has-error has-feedback') }}">
            {{ Form::label('secondary_email', 'Alternate Email') }}
            {{ Form::text('secondary_email', null, ['class'=>'form-control']) }}
            <span class="help-block">If your paying through PayPal and that account has a different address or your using a different email with google groups please enter it here</span>
            {{ Notification::getErrorDetail('secondary_email') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('password', 'has-error has-feedback') }}">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class'=>'form-control', 'autocomplete'=>'off']) }}
            {{ Notification::getErrorDetail('password') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('address_line_1', 'has-error has-feedback') }}">
            {{ Form::label('address_line_1', 'Address Line 1') }}
            {{ Form::text('address_line_1', null, ['class'=>'form-control', 'x-autocompletetype'=>'address-line-1']) }}
            {{ Notification::getErrorDetail('address_line_1') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('address_line_2', 'has-error has-feedback') }}">
            {{ Form::label('address_line_2', 'Address Line 2') }}
            {{ Form::text('address_line_2', null, ['class'=>'form-control', 'x-autocompletetype'=>'address-line-2']) }}
            {{ Notification::getErrorDetail('address_line_2') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('address_line_3', 'has-error has-feedback') }}">
            {{ Form::label('address_line_3', 'Address Line 3') }}
            {{ Form::text('address_line_3', null, ['class'=>'form-control', 'x-autocompletetype'=>'address-locality']) }}
            {{ Notification::getErrorDetail('address_line_3') }}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('address_line_4', 'has-error has-feedback') }}">
            {{ Form::label('address_line_4', 'Address Line 4') }}
            {{ Form::text('address_line_4', null, ['class'=>'form-control', 'x-autocompletetype'=>'region']) }}
            {{ Notification::getErrorDetail('address_line_4') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('address_postcode', 'has-error has-feedback') }}">
            {{ Form::label('address_postcode', 'Post Code') }}
            {{ Form::text('address_postcode', null, ['class'=>'form-control', 'x-autocompletetype'=>'postal-code']) }}
            {{ Notification::getErrorDetail('address_postcode') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('emergency_contact', 'has-error has-feedback') }}">
            {{ Form::label('emergency_contact', 'Emergency Contact') }}
            {{ Form::text('emergency_contact', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('emergency_contact') }}
            <span class="help-block">Please give us the name and contact details of someone we can contact if needed</span>
        </div>
    </div>
</div>


<div class="row">
    <p class="col-xs-12 col-md-8">
        Build Brighton is a community of people rather than a company and it operates largely on trust.<br />
        We require a profile photo as it allows the members to help recognise new faces.
    </p>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('profile_photo', 'has-error has-feedback') }}">
            {{ Form::label('profile_photo', 'Profile Photo', ['class'=>'control-label']) }}
            {{ Form::file('profile_photo', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('profile_photo') }}
            <span class="help-block">This must be a clear image of your face, its not much use for identification otherwise!</span>
            <span class="help-block">This photo will be displayed to members and may be used within the space, it will also be listed publicly on this site but you can turn that off below if you want.</span>
        </div>
        @if ($user->profile_photo)
        <div class="form-group">
            <strong>Existing Image</strong><br />
            <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" />
        </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('profile_photo_private', 'has-error has-feedback') }}">
            {{ Form::checkbox('profile_photo_private', true, null, ['class'=>'']) }}
            {{ Form::label('profile_photo_private', 'Make my photo private', ['class'=>'']) }}
            {{ Notification::getErrorDetail('profile_photo_private') }}
            <span class="help-block">If you want to block your photo from displaying outside Build Brighton please check this box although we would rather you didn't.</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
        <p></p>
    </div>
</div>

{{ Form::close() }}