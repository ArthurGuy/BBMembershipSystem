<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1E8C99">
    <title>@yield('meta-title', 'Member System') | Build Brighton</title>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,100' rel='stylesheet' type='text/css'>
    <link href="/css/main.css" rel="stylesheet">

    <script src="//www.google.com/jsapi"></script>
    <script>var BB = BB || {};</script>
    @if (App::environment() == 'production')
        @include('partials/rollbar-js')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        @if (Auth::guest())
            ga('create', 'UA-53813063-1', 'auto');
        @else
            ga('create', 'UA-53813063-1', { 'userId': '{{ Auth::user()->id }}', cookieDomain: 'auto' });
        @endif
        ga('send', 'pageview');
    </script>
    @endif
</head>
<body class="{{ $body_class or '' }}">

    @include('partials/main-sidenav')

<div id="bodyWrap">

    <header id="pageTitle">
        <div class="menuToggleButton">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="menu-label">Menu</span>
        </div>
        <div class="titles">
            <span class="hidden-xs">@yield('page-key-image')</span>
            <h1 class="title">@yield('page-title')</h1>
            <div class="pull-right action-buttons">@yield('page-action-buttons')</div>
        </div>

    </header>

    @yield('main-tab-bar')

    <div class="container-fluid">

        @yield('content')

    </div>

    <footer class="siteFooter">
        <div class="container-fluid">
            <div class="row">
                <div class="col col-sm-12">
                    <span class="text-muted">
                        <a href="https://github.com/ArthurGuy/BBMembershipSystem">
                            <img src="https://img.shields.io/badge/source-ArthurGuy%2FBBMS-blue.svg?style=flat-square" />
                        </a>
                        <a href="https://twitter.com/ArthurGuy">
                            <img src="https://img.shields.io/badge/author-@ArthurGuy-2DB94E.svg?style=flat-square" />
                        </a>
                        @if (!Auth::guest())
                        <span class="hidden-xs">
                            | <a href="https://github.com/ArthurGuy/BBMembershipSystem/issues">Report an Issue</a>
                        </span>
                        @endif
                    </span>
                    <span class="text-muted pull-right hidden-xs">
                        env: {{ env('APP_ENV', 'unknown') }} |
                        Hosted on <a href="https://www.digitalocean.com/?refcode=b71ca038f612">Digital Ocean</a> using <a href="https://forge.laravel.com">Laravel Forge</a>
                    </span>
                </div>
            </div>
        </div>
    </footer>

</div>

<div class="modalMask"></div>

    @include('partials/feedback-widget')

    @include('partials/js-data')

    <script src="//js.pusher.com/2.2/pusher.min.js" type="text/javascript"></script>
    <script src="/js/bundle.js"></script>

    @include('partials/flash-message')


    @yield('footer-js')

</body>
</html>