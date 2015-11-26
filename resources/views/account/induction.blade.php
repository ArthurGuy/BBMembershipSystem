@extends('layouts.main')

@section('meta-title')
{{ $user->name }} - Member Induction
@stop

@section('page-title')
    {{ $user->name }} - Member Induction
@stop

@section('page-key-image')
    {!! HTML::memberPhoto($user->profile, $user->hash, 100, '') !!}
@stop


@section('content')

    <div class="col-sm-12 col-lg-8 col-sm-offset-2">
        <p>
            Content goes here
        </p>
        <p>
            <span class="help-block"><a href="https://bbms.buildbrighton.com/resources/policy/rules" target="_blank">Build Brighton Rules</a></span>
        </p>
    </div>

    {!! Form::open(array('route' => ['account.induction.update', $user->id], 'class'=>'form-horizontal', 'method'=>'PUT')) !!}

    <div class="form-group {{ Notification::hasErrorDetail('induction_completed', 'has-error has-feedback') }}">
        <div class="col-sm-12 col-lg-8 col-sm-offset-2">
            {!! Form::checkbox('induction_completed', true, $user->induction_completed, ['class'=>'']) !!}
            {!! Form::label('induction_completed', 'I have received an induction and understand what is being asked of me', ['class'=>'']) !!}
            {!! Notification::getErrorDetail('induction_completed') !!}
        </div>
    </div>

    <div class="form-group {{ Notification::hasErrorDetail('rules_agreed', 'has-error has-feedback') }}">
        <div class="col-sm-12 col-lg-8 col-sm-offset-2">
            {!! Form::checkbox('rules_agreed', true, $user->rules_agreed, ['class'=>'']) !!}
            {!! Form::label('rules_agreed', 'I agree to the Build Brighton rules', ['class'=>'']) !!}
            {!! Notification::getErrorDetail('rules_agreed') !!}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-8 col-sm-offset-2">
            {!! Form::submit('Confirm', array('class'=>'btn btn-primary')) !!}
        </div>
    </div>


    {!! Form::close() !!}

@stop
