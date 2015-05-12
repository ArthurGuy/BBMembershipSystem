@extends('layouts.main')

@section('meta-title')
    Edit a piece of equipment
@stop

@section('page-title')
    <a href="{{ route('equipment.index') }}">Tools &amp; Equipment</a> > <a href="{{ route('equipment.show', $equipment->key) }}">{{ $equipment->name }}</a> > Edit
@stop

@section('content')

<div class="row" style="margin-bottom: 20px;">
    <div class="col-xs-12">

        {{ Form::model($equipment, array('route' => ['equipment.update', $equipment->key], 'class'=>'form-horizontal', 'files'=>true, 'method'=>'PUT')) }}

        @include('equipment/form')

        @if (!$equipment->obtained_at)
        <div class="form-group {{ Notification::hasErrorDetail('obtained_at', 'has-error has-feedback') }}">
            {{ Form::label('obtained_at', 'Date Obtained', ['class'=>'col-sm-3 control-label']) }}
            <div class="col-sm-9 col-lg-7">
                {{ Form::text('obtained_at', null, ['class'=>'form-control js-date-select']) }}
                <p class="help-block">When did Build Brighton obtain/purchase the item?</p>
                {{ Notification::getErrorDetail('obtained_at') }}
            </div>
        </div>
        @endif

        @if (!$equipment->removed_at)
        <div class="form-group {{ Notification::hasErrorDetail('removed_at', 'has-error has-feedback') }}">
            {{ Form::label('removed_at', 'Date Removed', ['class'=>'col-sm-3 control-label']) }}
            <div class="col-sm-9 col-lg-7">
                {{ Form::text('removed_at', null, ['class'=>'form-control js-date-select']) }}
                <p class="help-block">When did Build Brighton get rid of it?</p>
                {{ Notification::getErrorDetail('removed_at') }}
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
            </div>
        </div>

        {{ Form::close() }}


    </div>
</div>

<div class="row">
    <div class="col-xs-12">

        <div class="well">

            @if ($equipment->hasPhoto())
                <h4>Photos</h4>
                <div class="row">
                @for($i=0; $i < $equipment->getNumPhotos(); $i++)
                    <div class="col-xs-12 col-md-4 col-lg-2">
                    <img src="{{ $equipment->getPhotoUrl($i) }}" class="img-thumbnail" width="200" />
                    {{ Form::open(array('route' => ['equipment.photo.destroy', $equipment->key, $i], 'class'=>'form-horizontal', 'files'=>true, 'method'=>'DELETE')) }}
                    {{ Form::submit('Delete', array('class'=>'btn btn-primary')) }}
                    {{ Form::close() }}
                    </div>
                @endfor
                </div>
            @endif


            <h4>Add a new photo</h4>
            {{ Form::open(array('route' => ['equipment.photo.store', $equipment->key], 'class'=>'form-horizontal', 'files'=>true, 'method'=>'POST')) }}

            <div class="form-group {{ Notification::hasErrorDetail('photo', 'has-error has-feedback') }}">
                {{ Form::label('photo', 'Equipment Photo', ['class'=>'col-sm-3 control-label']) }}
                <div class="col-sm-9 col-lg-7">
                    <input name="photo" class="form-control" type="file" accept="image/*" capture="camera" id="inputPhoto">
                    <p class="help-block">Do you have a photo?</p>
                    {{ Notification::getErrorDetail('photo') }}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    {{ Form::submit('Upload', array('class'=>'btn btn-primary')) }}
                </div>
            </div>

            {{ Form::close() }}
        </div>

    </div>
</div>
@stop