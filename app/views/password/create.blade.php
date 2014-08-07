


    <div class="login-container">
        {{ Form::open(array('route' => 'password-reminder.store', 'class'=>'form-horizontal')) }}

        <div class="row">
            <div class="col-xs-12">
                <h1>Password Reset</h1>
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
                        {{ Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'Email']) }}
                        {{ $errors->first('email', '<span class="glyphicon glyphicon-remove form-control-feedback"></span>') }}
                    </div>
                </div>

                {{ Form::submit('Go', array('class'=>'btn btn-primary btn-block')) }}
        </div>
        <div class="row bottom-links">
            <div class="col-xs-12">
                <a href="{{ route('login') }}">Login</a> |
                <a href="{{ route('register') }}">Become a member</a>
            </div>
        </div>

        {{ Form::close() }}
    </div>
