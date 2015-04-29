@extends('layouts.main')

@section('meta-title')
    Record a new piecemanufacturing
@stop

@section('page-title')
    Record a new piece of equipment
@stop

@section('content')

<div class="col-xs-12">

    {{ Form::open(array('route' => 'equipment.store', 'class'=>'form-horizontal', 'files'=>true)) }}

    <div class="form-group {{ Notification::hasErrorDetail('name', 'has-error has-feedback') }}">
        {{ Form::label('name', 'Name', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('name', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('name') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('family_name', 'has-error has-feedback') }}">
        {{ Form::label('manufacturer', 'Manufacturer', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('manufacturer', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('manufacturer') }}
        </div>
    </div>


    <div class="form-group {{ Notification::hasErrorDetail('model_number', 'has-error has-feedback') }}">
        {{ Form::label('model_number', 'Model Number', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('model_number', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('model_number') }}
        </div>
    </div>


    <div class="form-group {{ Notification::hasErrorDetail('serial_number', 'has-error has-feedback') }}">
        {{ Form::label('serial_number', 'Serial Number', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('serial_number', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('serial_number') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('colour', 'has-error has-feedback') }}">
        {{ Form::label('colour', 'Colour', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('colour', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('colour') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('room', 'has-error has-feedback') }}">
        {{ Form::label('room', 'Room', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::select('room', ['workshop'=>'Dirty Workshop', 'digital-manufacturing'=>'Digital Manufacturing', 'main-room'=>'Main Room'], null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('room') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('detail', 'has-error has-feedback') }}">
        {{ Form::label('detail', 'Detail', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('detail', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('detail') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('key', 'has-error has-feedback') }}">
        {{ Form::label('key', 'Key', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('key', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('key') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('device_key', 'has-error has-feedback') }}">
        {{ Form::label('device_key', 'Device Key', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('device_key', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('device_key') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('description', 'has-error has-feedback') }}">
        {{ Form::label('description', 'Description', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::textarea('description', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('description') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('help_text', 'has-error has-feedback') }}">
        {{ Form::label('help_text', 'Help Text', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::textarea('help_text', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('help_text') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('requires_induction', 'has-error has-feedback') }}">
        {{ Form::label('requires_induction', 'Requires Induction', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::select('requires_induction', [1=>'Yes', 0=>'No'], 0, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('requires_induction') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('working', 'has-error has-feedback') }}">
        {{ Form::label('working', 'Working', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::select('working', [1=>'Yes', 0=>'No'], 1, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('working') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('permaloan', 'has-error has-feedback') }}">
        {{ Form::label('permaloan', 'Permaloan', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::select('permaloan', [1=>'Yes', 0=>'No'], 0, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('permaloan') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('permaloan_user_id', 'has-error has-feedback') }}">
        {{ Form::label('permaloan_user_id', 'Permaloan User', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::select('permaloan_user_id', [''=>'']+$memberList, null, ['class'=>'form-control', 'style'=>'margin-right:10px; width:150px;']) }}
            {{ Notification::getErrorDetail('permaloan_user_id') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('access_fee', 'has-error has-feedback') }}">
        {{ Form::label('access_fee', 'Access Fee', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            <div class="input-group">
                <div class="input-group-addon">&pound;</div>
                {{ Form::input('number', 'access_fee', 0, ['class'=>'form-control', 'min'=>'0', 'step'=>'1']) }}
            </div>
            {{ Notification::getErrorDetail('access_fee') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('obtained_at', 'has-error has-feedback') }}">
        {{ Form::label('obtained_at', 'Date Obtained', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('obtained_at', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('obtained_at') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('removed_at', 'has-error has-feedback') }}">
        {{ Form::label('removed_at', 'Date Removed', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::text('removed_at', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('removed_at') }}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('photo', 'has-error has-feedback') }}">
        {{ Form::label('photo', 'Profile Photo', ['class'=>'col-sm-3 control-label']) }}
        <div class="col-sm-9 col-lg-7">
            {{ Form::file('photo', null, ['class'=>'form-control']) }}
            {{ Notification::getErrorDetail('photo') }}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}
        </div>
    </div>


    {{ Form::close() }}

</div>

@stop


@section('footer-js')
    <script>
        $(document).ready(function() { $("select").select2({dropdownAutoWidth:false}); });
    </script>
@stop