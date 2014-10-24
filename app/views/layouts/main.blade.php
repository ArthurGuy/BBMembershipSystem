<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Member System') | Build Brighton</title>

    <link href="/css/all.min.css" rel="stylesheet">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//www.google.com/jsapi"></script>

    @if (App::environment() == 'production')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-53813063-1', 'auto');
        ga('send', 'pageview');
    </script>
    @endif
</head>
<body class="{{ $body_class or '' }}">

    <nav class="mainSidenav" role="navigation">

        <span class="sidenav-brand">
            <a href="{{ route('home') }}"><img class="" src="/img/logo.png" height="50" /></a>
        </span>

        <ul class="nav navbar-nav">
            <li class="detail-link">
                <a href="{{ route('members.index') }}">
                    Members
                </a>
            </li>
            <li class="detail-link">
                <a href="{{ route('storage_boxes.index') }}">
                    Member Storage
                </a>
            </li>
            <li class="detail-link">
                <a href="{{ route('equipment.index') }}">
                    Tools and Equipment
                </a>
            </li>
            <li class="detail-link">
                <a href="{{ route('activity.index') }}">
                    Activity
                </a>
            </li>
            <li class="detail-link">
                <a href="{{ route('stats.index') }}">
                    Stats
                </a>
            </li>
            @if (!Auth::guest() && Auth::user()->isAdmin())

            <li class="detail-link">
                <a href="{{ route('proposals.index') }}">
                    Proposals (Admin)
                </a>
            </li>

            <li class="detail-link">
                <a href="{{ route('account.index') }}">
                    Members (Admin)
                </a>
            </li>

            @endif
        </ul>

        <ul class="nav navbar-nav secondaryNav">
            @if (Auth::guest())
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Become a Member</a></li>
            @else

                <li><a href="{{ route('account.show', Auth::id()) }}">Your Membership</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>

                @if (Auth::user()->isAdmin())
                <li>
                    <span class="navbar-text">
                        <span class="label label-danger">Admin</span>
                    </span>
                </li>
                @endif

                <li>
                    <span class="navbar-text">
                        {{ HTML::statusLabel(Auth::user()->status) }}
                    </span>
                </li>

            @endif
        </ul>
    </nav>

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

            <a class="navbar-brand brand-name-short" href="{{ route('home') }}" title="Build Brighton Member System">BB Member System</a>
            <a class="navbar-brand" href="">BB</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="detail-link">
                    <a href="{{ route('members.index') }}">
                        Members
                    </a>
                </li>
                <li class="detail-link">
                    <a href="{{ route('storage_boxes.index') }}">
                        Member Storage
                    </a>
                </li>
                <li class="detail-link">
                    <a href="{{ route('equipment.index') }}">
                        Tools and Equipment
                    </a>
                </li>
                <li class="detail-link">
                    <a href="{{ route('activity.index') }}">
                        Activity
                    </a>
                </li>
                <li class="detail-link">
                    <a href="{{ route('stats.index') }}">
                        Stats
                    </a>
                </li>
                @if (!Auth::guest() && Auth::user()->isAdmin())

                <li class="detail-link">
                    <a href="{{ route('proposals.index') }}">
                        Proposals (Admin)
                    </a>
                </li>

                <li class="detail-link">
                    <a href="{{ route('account.index') }}">
                        Members (Admin)
                    </a>
                </li>

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
                        {{ HTML::statusLabel(Auth::user()->status) }}
                    </span>
                </li>

                <li><a href="{{ route('account.show', Auth::id()) }}">Your Membership</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="bodyWrap">

    <header id="pageTitle">
        <div class="titles">
            <h1 class="title">@yield('main-title', 'Page Title')</h1>
        </div>
    </header>

    @yield('main-tab-bar')

    <div class="container-fluid">

        @include('partials/flash')

        <div class="top-alerts">
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
        </div>

        {{ $content or '' }}
        @yield('content')

    </div>

    <footer class="siteFooter">
        <div class="container-fluid">
            <div class="row">
                <div class="col col-sm-12">
                    <span class="text-muted">
                        <a href="https://github.com/ArthurGuy/BBMembershipSystem">
                            <img src="https://img.shields.io/badge/source-ArthurGuy%2FBBMembershipSystem-blue.svg?style=flat-square" />
                        </a>
                        <a href="https://twitter.com/ArthurGuy">
                            <img src="https://img.shields.io/badge/author-@ArthurGuy-2DB94E.svg?style=flat-square" />
                        </a>
                        @if (!Auth::guest())
                        | <a href="https://github.com/ArthurGuy/BBMembershipSystem/issues">Report an Issue</a>
                        @endif
                    </span>
                    <span class="text-muted pull-right hidden-xs">
                        Hosted on <a href="https://www.digitalocean.com/?refcode=b71ca038f612">Digital Ocean</a> using <a href="https://forge.laravel.com">Laravel Forge</a>
                    </span>
                </div>
            </div>
        </div>
    </footer>

</div>

    @if (!Auth::guest())
    <div class="feedbackWidgetButtonWrap hidden-sm">
        <div id="feedbackWidgetButton" data-toggle="modal" data-target="#feedbackWidgetModal">
            <span class="glyphicon glyphicon-bullhorn"></span> Feedback
        </div>
    </div>
    <div class="modal fade" id="feedbackWidgetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Member System Feedback</h4>
                </div>
                {{ Form::open(array('method'=>'POST', 'route' => ['feedback.store'], 'class'=>'js-feedbackModalForm js-ajaxForm', 'data-successFunction'=>'feedbackFormSuccess', 'data-errorFunction'=>'feedbackFormError')) }}
                <div class="modal-body">
                    <p>If you have any comments or thoughts or ideas about the operation of the BBMS system we would love to hear them</p>

                    <div class="form-group js-field-comments">
                        {{ Form::label('comments', 'Your Thoughts') }}
                        {{ Form::textarea('comments', null, ['class'=>'form-control', ''=>'']) }}
                        <span class="help-block js-errorMessages"></span>
                    </div>

                </div>
                <div class="modal-footer">
                    {{ Form::button('Close', ['class'=>'btn btn-default', 'data-dismiss'=>'modal']) }}
                    {{ Form::submit('Submit', ['class'=>'btn btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endif

    <!-- Inline for now - this will be fixed at some stage -->
    <script>
    $('.js-ajaxForm').on('submit', function(event) {
        event.preventDefault();

        //Get the success and failure functions for the form
        var successFunction = $(this).attr('data-successFunction');
        var errorFunction = $(this).attr('data-errorFunction');

        $(this).find('input[type=submit]').attr('disabled', 'disabled');

        //Clear the error messages
        $(this).find('.js-errorMessages').text('');
        $(this).find('.has-error').removeClass('has-error');

        //Store the context for the callbacks
        var $form = $(this);
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: [function() {
                $form.find('input[type=submit]').removeAttr('disabled');
            }, window[successFunction]],
            error: [function() {
                $form.find('input[type=submit]').removeAttr('disabled');
            }, window[errorFunction]],
            dataType: 'json'
        });
    });
    function feedbackFormSuccess(data) {
        if (data.success) {
            $('#feedbackWidgetModal').modal('hide');
            $('.js-feedbackModalForm textarea').val('');
        }
    }
    function feedbackFormError(xhr, status, error) {
        var errors = eval("(" + xhr.responseText + ")");
        for(var n in errors) {
            var $field = $('.js-feedbackModalForm').find('.js-field-'+n+'');
            $field.addClass('has-error');
            $field.find('.js-errorMessages').text(errors[n]);
        }
    }
    </script>


    @include('partials/page-js')

    <script src="/js/lib/bootstrap.min.js"></script>
    <script src="/js/lib/select2.min.js"></script>
    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
    <script>
        $('[data-toggle=tooltip]').tooltip({});

        $('.js-show-alter-subscription-amount').click(function(event) {
            event.preventDefault();
            $('.js-alter-subscription-amount-form').removeClass('hidden');
            $(this).hide();
        });
    </script>

    @yield('footer-js')



</body>
</html>