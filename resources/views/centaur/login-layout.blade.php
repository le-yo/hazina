<!DOCTYPE html>
<html class="fill" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title> @yield('title')</title>

        <!-- Bootstrap - Latest compiled and minified CSS -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/simple-line-icons/css/simple-line-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
            <script src="/plugins/simple-line-icons/js/icons-lte-ie7.js"></script>
        <![endif]-->
    </head>
    <body>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-default guest-nav" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        {{--<img src="/images/logo.png" class="logo img-responsive" alt="WatuCredit">--}}
                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        {{--<li class="{{ Request::is('/*') ? 'active' : '' }}"><a href="{{ route('home') }}">Home</a></li>--}}
                    </ul>
                    {{--<ul class="nav navbar-nav navbar-right">--}}
                        {{--@if (Sentinel::check())--}}
                            {{--<li><p class="navbar-text">{{ Sentinel::getUser()->email }}</p></li>--}}
                            {{--<li><a class="btn button btn-login button--antiman"  href="{{ route('auth.logout') }}"><i class="icon-lock"></i>&nbsp;Log Out</a></li>--}}
                        {{--@else--}}
                            {{--<li class="{{ Request::is('login*') ? '' : '' }}"><a class="btn button btn-login button--antiman" href="{{ route('auth.login.form') }}"><i class="icon-lock"></i>&nbsp;Login</a></li>--}}
                        {{--@endif--}}
                    {{--</ul>--}}
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>
        @yield('content')

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="{{ asset('plugins/jquery/jquery-2.1.3.min.js') }}"></script>
        <!-- Latest compiled and minified Bootstrap JavaScript -->
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('js/restfulizer.js') }}"></script>
        <!-- Custom Javascript -->
        <script src="{{ asset('js/custom-scripts.js') }}"></script>
    </body>
</html>