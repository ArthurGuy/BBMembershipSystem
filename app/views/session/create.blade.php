


    <div class="login-container">
        {{ Form::open(array('route' => 'session.store', 'class'=>'form-horizontal')) }}

        <div class="row">
            <div class="col-xs-12">
                <!--<img class="login-logo" src="/img/logo.png" height="70" />-->
                <h1>Login</h1>
            </div>
        </div>

        @if ($errors->has('email') || $errors->has('password'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
                {{ $errors->first('password') }}
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

                {{ Form::submit('Go', array('class'=>'btn btn-primary btn-block')) }}
        </div>
        <div class="row bottom-links">
            <div class="col-xs-12">
                <a href="{{ route('register') }}">Reset your password</a> |
                <a href="{{ route('register') }}">Become a member</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
