@extends('layouts.main')


@section('content')

	<div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1>Build Brighton</h1>
                <p class="lead">
                    Welcome to the Build Brighton Member System
                </p>
                <p>
                    You can use this site to sign up to Build Brighton as well as managing your subscription and various other aspects of your membership.
                </p>
                <p>
                    <a href="{{ route('register') }}" class="btn btn-primary">Become a member</a>
                </p>
                <p>
                    For more information on Build Brighton please visit <a href="http://www.buildbrighton.com">www.buildbrighton.com</a>
                </p>
                <p>
                Already part of Build Brighton then <a href="{{ route('login') }}" class="">Log in</a>
                </p>
            </div>
        </div>
	</div>

@stop