<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="page-header">
            <h1>Login</h1>
        </div>
    </div>
</div>

{{ Form::open(array('route' => 'session.store')) }}

<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', null, ['class'=>'form-control']) }}
            {{ $errors->first('email', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class'=>'form-control']) }}
            {{ $errors->first('password', '<span class="help-block">:message</span>') }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        {{ Form::submit('Login', array('class'=>'btn btn-primary')) }}
    </div>
</div>

{{ Form::close() }}