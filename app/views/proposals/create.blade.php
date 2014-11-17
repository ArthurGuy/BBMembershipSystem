@extends('layouts.main')

@section('meta-title')
Create a Proposal
@stop

@section('page-title')
Create a Proposal
@stop

@section('content')

    <div class="col-xs-12">
        {{ Form::open(array('route' => 'proposals.store', 'class'=>'', 'method'=>'POST')) }}

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('title', 'has-error has-feedback') }}">
                    {{ Form::label('title', 'Title') }}
                    {{ Form::text('title', null, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('title') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('description', 'has-error has-feedback') }}">
                    {{ Form::label('description', 'Description') }}
                    {{ Form::textarea('description', null, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('description') }}
                    <p>HTML can be included here. Don't forget to put links in "a" tags</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8">
                <div class="form-group {{ Notification::hasErrorDetail('end_date', 'has-error has-feedback') }}">
                    {{ Form::label('end_date', 'End Date') }}
                    {{ Form::text('end_date', $endDate, ['class'=>'form-control']) }}
                    {{ Notification::getErrorDetail('end_date') }}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                {{ Form::submit('Create', array('class'=>'btn btn-primary')) }}
            </div>
        </div>

        {{ Form::close() }}
    </div>


@stop