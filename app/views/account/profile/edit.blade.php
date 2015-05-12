@extends('layouts.main')

@section('meta-title')
Fill in your profile
@stop

@section('page-title')
Fill in your profile
@stop

@section('content')
<div class="col-xs-12 col-md-8 col-md-offset-2">

<div class="page-header">
    <p>This information will be shared with others, enter as much or as little as you want</p>
</div>

{{ Form::model($profileData, array('route' => ['account.profile.update', $userId], 'method'=>'PUT', 'files'=>true)) }}


<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('skills', 'has-error has-feedback') }}">
            {{ Form::label('skills', 'Skills') }}
            {{ Form::select('skills[]', $skills, null, ['class'=>'form-control advanced-dropdown', 'multiple']) }}
            {{ Notification::getErrorDetail('skills') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('tagline', 'has-error has-feedback') }}">
            {{ Form::label('tagline', 'Tagline') }}
            {{ Form::text('tagline', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('tagline') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('description', 'has-error has-feedback') }}">
            {{ Form::label('description', 'Description') }}
            {{ Form::textarea('description', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('description') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('twitter', 'has-error has-feedback') }}">
            {{ Form::label('twitter', 'Twitter') }}
            <div class="input-group">
                <div class="input-group-addon">https://twitter.com/</div>
                {{ Form::text('twitter', null, ['class'=>'form-control']) }}
            </div>
            {{ Notification::getErrorDetail('twitter') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('facebook', 'has-error has-feedback') }}">
            {{ Form::label('facebook', 'Facebook') }}
            <div class="input-group">
                <div class="input-group-addon">https://www.facebook.com/</div>
                {{ Form::text('facebook', null, ['class'=>'form-control']) }}
            </div>
            {{ Notification::getErrorDetail('facebook') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('google_plus', 'has-error has-feedback') }}">
            {{ Form::label('google_plus', 'Google+') }}
            <div class="input-group">
                <div class="input-group-addon">https://plus.google.com/+</div>
                {{ Form::text('google_plus', null, ['class'=>'form-control']) }}
            </div>
            {{ Notification::getErrorDetail('google_plus') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('github', 'has-error has-feedback') }}">
            {{ Form::label('github', 'GitHub') }}
            <div class="input-group">
                <div class="input-group-addon">https://github.com/</div>
                {{ Form::text('github', null, ['class'=>'form-control']) }}
            </div>
            {{ Notification::getErrorDetail('github') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('website', 'has-error has-feedback') }}">
            {{ Form::label('website', 'Website') }}
            {{ Form::text('website', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('website') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('irc', 'has-error has-feedback') }}">
            {{ Form::label('irc', 'IRC') }}
            {{ Form::text('irc', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('irc') }}
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
        <div class="form-group {{ Notification::hasErrorDetail('new_profile_photo', 'has-error has-feedback') }}">
            {{ Form::label('new_profile_photo', 'Profile Photo', ['class'=>'control-label']) }}
            {{ Form::file('new_profile_photo', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('new_profile_photo') }}
            <span class="help-block"><strong>This must be a clear image of your face</strong>, (passport photo style) its not much use for identification otherwise!</span>
            <span class="help-block">This photo will be displayed to members and may be used within the space, it will also be listed publicly on this site but you can turn that off below if you want.</span>
        </div>
        <div class="row">
        @if ($profileData->profile_photo)
        <div class="col-xs-6">
            <div class="form-group">
                <strong>Existing Profile Image</strong><br />
                <img src="{{ \BB\Helpers\UserImage::thumbnailUrl($user->hash) }}" />
            </div>
        </div>
        @endif
        @if ($profileData->new_profile_photo)
        <div class="col-xs-6">
            <div class="form-group">
                <strong>New Profile Image - pending review</strong><br />
                <img src="{{ \BB\Helpers\UserImage::newThumbnailUrl($user->hash) }}" />
            </div>
        </div>
        @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="form-group {{ Notification::hasErrorDetail('profile_photo_private', 'has-error has-feedback') }}">
            {{ Form::checkbox('profile_photo_private', true, null, ['class'=>'']) }}
            {{ Form::label('profile_photo_private', 'Make my photo private', ['class'=>'']) }}
            {{ Notification::getErrorDetail('profile_photo_private') }}
            <span class="help-block">This blocks your photo from displaying outside of Build Brighton.</span>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-12 col-md-8">
        {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}
        <p></p>
    </div>
</div>

{{ Form::close() }}

</div>
@stop