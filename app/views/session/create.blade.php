<div class="page-header">
    <h1>Login</h1>
</div>

{{ Form::open(array('route' => 'session.store')) }}

<div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('email', 'Email') }}
    {{ Form::text('email', null, ['class'=>'form-control']) }}
    {{ $errors->first('email', '<span class="help-block">:message</span>') }}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
    {{ Form::label('password', 'Password') }}
    {{ Form::password('password', ['class'=>'form-control']) }}
    {{ $errors->first('password', '<span class="help-block">:message</span>') }}
</div>



{{ Form::submit('Login', array('class'=>'btn btn-primary')) }}


{{ Form::close() }}