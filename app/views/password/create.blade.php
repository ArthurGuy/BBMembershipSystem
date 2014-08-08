


    <div class="login-container col-xs-12 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
        {{ Form::open(array('route' => 'password-reminder.store', 'class'=>'')) }}

        <div class="row">
            <div class="col-xs-12">
                <h1>Password Reset</h1>
                <p>
                    Forgotten your password or is it just not working?<br />
                    Enter your email address here and watch out for the reset email
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

        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @endif

        <div class="row">
            <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }}">
                <div class="col-xs-12">
                    {{ Form::input('email', 'email', null, ['class'=>'form-control', 'placeholder'=>'Email']) }}
                    {{ $errors->first('email', '<span class="glyphicon glyphicon-remove form-control-feedback"></span>') }}
                </div>
            </div>

            <div class="col-xs-12">
                {{ Form::submit('Go', array('class'=>'btn btn-primary btn-block')) }}
            </div>
        </div>
        <div class="row bottom-links">
            <div class="col-xs-12">
                <a href="{{ route('login') }}">Login</a> |
                <a href="{{ route('register') }}">Become a member</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
