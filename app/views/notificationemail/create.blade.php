@extends('layouts.main')

@section('meta-title')
Email all active members
@stop

@section('page-title')
Email Members
@stop

@section('content')

    <div class="row page-header">
        <div class="col-xs-12">
            <p>Send an email to all the active members</p>
        </div>
    </div>

    <div class="col-xs-12 col-md-12 col-lg-8">
        {{ Form::open(array('route' => 'notificationemail.store', 'class'=>'', 'method'=>'POST')) }}

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('subject', 'has-error has-feedback') }}">
                    {{ Form::label('subject', 'Subject') }}
                    {{ Form::text('subject', null, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('subject') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('message', 'has-error has-feedback') }}">
                    {{ Form::label('message', 'Message') }}
                    {{ Form::textarea('message', null, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('message') }}
                    <p>The email will be addressed to the user and contain the standard signature, the message above will be placed inbetween</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {{ Form::submit('Send', array('class'=>'btn btn-primary')) }}
                {{ Form::checkbox('send_to_all') }}
                {{ Form::label('send_to_all', 'Send the message to everyone, not just yourself') }}
                <p>Make sure everything is alright as the message will be sent as soon as you click send, if your just
                testing make sure you have the message elsewhere as it wont be here when the page loads</p>
            </div>
        </div>

        {{ Form::close() }}
    </div>


@stop