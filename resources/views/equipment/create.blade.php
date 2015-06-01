@extends('layouts.main')

@section('meta-title')
    Record a new piece of equipment
@stop

@section('page-title')
    Record a new piece of equipment
@stop

@section('content')

<div class="col-xs-12">

    {!! Form::open(array('route' => 'equipment.store', 'class'=>'form-horizontal', 'files'=>true)) !!}

    @include('equipment/form')

    <div class="form-group {{ Notification::hasErrorDetail('obtained_at', 'has-error has-feedback') }}">
        {!! Form::label('obtained_at', 'Date Obtained', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('obtained_at', null, ['class'=>'form-control js-date-select']) !!}
            <p class="help-block">When did Build Brighton obtain/purchase the item?</p>
            {!! Notification::getErrorDetail('obtained_at') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('removed_at', 'has-error has-feedback') }}">
        {!! Form::label('removed_at', 'Date Removed', ['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 col-lg-7">
            {!! Form::text('removed_at', null, ['class'=>'form-control js-date-select']) !!}
            <p class="help-block">When did Build Brighton get rid of it?</p>
            {!! Notification::getErrorDetail('removed_at') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
            {!! Form::submit('Save', array('class'=>'btn btn-primary')) !!}
        </div>
    </div>

    {!! Form::close() !!}

</div>

@stop
