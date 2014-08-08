


    <div class="login-container col-xs-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        {{ Form::open(array('route' => 'password.reset.complete', 'class'=>'')) }}

        <div class="row">
            <div class="col-xs-12">
                <h1>Set a new Password</h1>
                <p>
                    Enter your email address and choose a new password for your account
                </p>
            </div>
        </div>

        @if ($errors->count() > 0)
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br />
                @endforeach
            </div>
        @endif

        <div class="row">
            <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                <div class="col-xs-12">
                    {{ Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'Email']) }}
                    {{ $errors->first('email', '<span class="glyphicon glyphicon-remove form-control-feedback"></span>') }}
                </div>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }}">
                <div class="col-xs-12">
                    {{ Form::password('password', ['class'=>'form-control', 'placeholder'=>'Password']) }}
                    {{ $errors->first('password', '<span class="glyphicon glyphicon-remove form-control-feedback"></span>') }}
                </div>
            </div>

            <div class="col-xs-12">
                {{ Form::submit('Go', array('class'=>'btn btn-primary btn-block')) }}
            </div>
        </div>
        <div class="row bottom-links">
            <div class="col-xs-12">
                <a href="{{ route('password-reminder.create') }}">Reset your password</a> |
                <a href="{{ route('register') }}">Become a member</a>
            </div>
        </div>
        {{ Form::hidden('token', $token) }}
        {{ Form::close() }}
    </div>
