@extends('layouts.main')

@section('meta-title')
Update a Proposal
@stop

@section('page-title')
Update a Proposal
@stop

@section('content')

    <div class="col-xs-12">
        {{ Form::open(array('route' => ['proposals.update', $proposal->id], 'class'=>'', 'method'=>'POST')) }}

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('title', 'has-error has-feedback') }}">
                    {{ Form::label('title', 'Title') }}
                    {{ Form::text('title', $proposal->title, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('title') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('description', 'has-error has-feedback') }}">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', $proposal->description, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('description') }}
                    <p>Please use markdown for formatting proposal text</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('start_date', 'has-error has-feedback') }}">
                    {{ Form::label('start_date', 'Start Date') }}
                    {{ Form::text('start_date', $proposal->start_date->format('Y-m-d'), ['class'=>'form-control js-date-select']) }}
                    {{ Notification::getErrorDetail('start_date') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('end_date', 'has-error has-feedback') }}">
                    {{ Form::label('end_date', 'End Date') }}
                    {{ Form::text('end_date', $proposal->end_date->format('Y-m-d'), ['class'=>'form-control js-date-select']) }}
                    {{ Notification::getErrorDetail('end_date') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {{ Form::submit('Update', array('class'=>'btn btn-primary')) }}
            </div>
        </div>

        {{ Form::close() }}
    </div>


@stop