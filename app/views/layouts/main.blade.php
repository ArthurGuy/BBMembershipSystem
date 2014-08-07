<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Build Brighton Member Management</title>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script type="text/javascript" src="http://www.google.com/jsapi"></script>

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
</head>
<body>

<nav class="navbar navbar-default " role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">

            <a class="navbar-brand brand-logo" href="{{ route('home') }}"><img class="" src="/img/logo.png" height="40" /></a>


            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand brand-name-full" href="{{ route('home') }}">Build Brighton Member System</a>
            <a class="navbar-brand brand-name-short" href="{{ route('home') }}" title="Build Brighton Member System">BB Member System</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if (!Auth::guest() && Auth::user()->isAdmin())
                    <li class=""><a href="{{ route('account.index') }}">Accounts</a></li>
                @endif
            </ul>


            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Become a Member</a></li>
                @else

                @if (Auth::user()->isAdmin())
                <li>
                    <span class="navbar-text">
                        <span class="label label-danger">Admin</span>
                    </span>
                </li>
                @endif

                <li>
                    <span class="navbar-text">
                        {{ User::statusLabel(Auth::user()->status) }}
                    </span>
                </li>

                <li><a href="{{ route('account.show', Auth::id()) }}">Your Account</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">

    @if($errors->any())
    <div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>{{ Session::get('success') }}</div>
    @endif

    {{ $content }}

</div>


<script src="/js/bootstrap.min.js"></script>

</body>
</html>