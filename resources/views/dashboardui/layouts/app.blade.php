<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title> @yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,600" rel="stylesheet">

    <!-- Favicon -->
    <link rel="apple-touch-icon" href="dashboardui/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('plugins/jquery.dataTables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="dashboardui/css/main.min.css">
</head>
<body>

<body class="o-page">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->
<div class="o-page__sidebar js-page-sidebar">
@include('dashboardui.partials.sidebar')
</div>

<main class="o-page__content">
            @include('dashboardui.partials.navbar')
            @yield('content');
</main>

{{--<nav class="navbar navbar-default">--}}
    {{--<div class="container">--}}
        {{--<!-- Brand and toggle get grouped for better mobile display -->--}}
        {{--<div class="navbar-header">--}}
            {{--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">--}}
                {{--<span class="sr-only">Toggle navigation</span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
                {{--<span class="icon-bar"></span>--}}
            {{--</button>--}}
            {{--<a class="navbar-brand" href="{{ route('home') }}">Payments Dashboard</a>--}}
        {{--</div>--}}
        {{--<!-- Collect the nav links, forms, and other content for toggling -->--}}
        {{--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">--}}
            {{--<ul class="nav navbar-nav">--}}
                {{--<li class="dropdown {{ Request::is('/*') || Request::is('short-term/loans*') ? 'active' : '' }}">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="icon-wallet"></i>&nbsp;Short Term Loans&nbsp;<span class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="{{ Request::is('/*') ? 'active' : '' }}"><a href="{{ route('home') }}">Payments</a></li>--}}
                {{--<li class="{{ Request::is('short-term/loans*') ? 'active' : '' }}"><a href="{{ route('short-term.loans') }}">Loans</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="dropdown {{ Request::is('business-loan*') ? 'active' : '' }}">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="icon-briefcase"></i>&nbsp;Business Loans<span class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="{{ Request::is('business-loan/payments*') ? 'active' : '' }}"><a href="{{ route('business-loan.payments') }}">Payments</a></li>--}}
                {{--<li class="{{ Request::is('business-loan/loans*') ? 'active' : '' }}"><a href="{{ route('business-loan.loans') }}">Loans</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--<li class="dropdown {{ Request::is('payday-loan*') ? 'active' : '' }}">--}}
                {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true"><i class="icon-calendar"></i>&nbsp;Payday Loans<span class="caret"></span></a>--}}
                {{--<ul class="dropdown-menu">--}}
                {{--<li class="{{ Request::is('payday-loan/payments*') ? 'active' : '' }}"><a href="{{ route('payday-loan.payments') }}">Payments</a></li>--}}
                {{--<li class="{{ Request::is('payday-loan/loans*') ? 'active' : '' }}"><a href="{{ route('payday-loan.loans') }}">Loans</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                {{--@if (Auth::check() && Auth::getUser()->hasRole('admin'))--}}
                {{--<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ route('users.create') }}"><i class="icon-people"></i>&nbsp;Users</a></li>--}}
                {{--<li class="{{ Request::is('roles*') ? 'active' : '' }}"><a href="{{ route('users.create') }}"><i class="icon-list"></i>&nbsp;Roles</a></li>--}}
                {{--@endif--}}
            {{--</ul>--}}
            {{--<ul class="nav navbar-nav navbar-right">--}}
                {{--@if (Auth::check())--}}
                    {{--<li class="dropdown">--}}
                        {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">--}}
                            {{--{{ Auth::user()->name }} <span class="caret"></span>--}}
                        {{--</a>--}}

                        {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ url('/users') }}"><i class="icon-people"></i>&nbsp;Users</a></li>--}}
                            {{--<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ url('/preapproved-clients') }}"><i class="icon-people"></i>Preapproved Clients</a></li>--}}

                            {{--<li>--}}
                                {{--<a href="{{ route('logout') }}"--}}
                                   {{--onclick="event.preventDefault();--}}
                                                                             {{--document.getElementById('logout-form').submit();">--}}
                                    {{--Logout--}}
                                {{--</a>--}}

                                {{--<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">--}}
                                    {{--{{ csrf_field() }}--}}
                                {{--</form>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--                            <li><p class="navbar-text">{{ Auth::getUser()->email }}</p></li>--}}
                    {{--<li><a href="{{ route('logout') }}">Log Out</a></li>--}}
                {{--@else--}}
                    {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                {{--@endif--}}
            {{--</ul>--}}
        {{--</div><!-- /.navbar-collapse -->--}}
    {{--</div><!-- /.container-fluid -->--}}
{{--</nav>--}}
{{--<div class="container">--}}
    {{--@include('centaur.notifications')--}}
    {{--@yield('content')--}}
{{--</div>--}}
</body>
<script src="dashboardui/js/main.min.js"></script>
<script src="{{ asset('plugins/jquery/jquery-2.1.3.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.dataTables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('js/restfulizer.js') }}"></script>
@stack('scripts')
</html>