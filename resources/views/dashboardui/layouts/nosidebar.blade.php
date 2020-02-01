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
{{--    <link rel="apple-touch-icon" href="{{ asset('dashboardui/img/apple-touch-icon.png) }}">--}}
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="{{ asset('plugins/jquery.dataTables/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboardui/css/main.css') }}">
</head>
<body>

<body class="o-page">
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<header class="c-navbar">
    <a class="c-navbar__brand" href="#!">
        <img src="{{ asset('dashboardui/img/logo.png') }}" alt="Logo">
    </a>

    <!-- Navigation items that will be collapes and toggle in small viewports -->
    <nav class="c-nav collapse" id="main-nav">
        <ul class="c-nav__list">
            <li class="c-nav__item">
                <a class="c-nav__link" href="/">Home</a>
            </li>
        </ul>
    </nav>
    <!-- // Navigation items  -->

    <div class="c-field has-icon-right c-navbar__search u-ml-auto u-hidden-down@tablet">
                <span class="c-field__icon">
                    <i class="fa fa-search"></i>
                </span>

        {{--<label class="u-hidden-visually" for="navbar-search">Seach</label>--}}
        {{--<input class="c-input" id="navbar-search" type="text" placeholder="Search">--}}
    </div>

    <div class="c-dropdown u-ml-medium dropdown">
        <a  class="c-avatar c-avatar--xsmall has-dropdown dropdown-toggle" href="#" id="dropdwonMenuAvatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="c-avatar__img" src="{{ asset('dashboardui/img/avatar-72.jpg') }}" alt="User's Profile Picture">
        </a>

        <div class="c-dropdown__menu dropdown-menu dropdown-menu-right" aria-labelledby="dropdwonMenuAvatar">
            <a class="c-dropdown__item dropdown-item" href="#">Edit Profile</a>
            <a class="c-dropdown__item dropdown-item" href="#">View Activity</a>
            <a class="c-dropdown__item dropdown-item" href="#">Manage Roles</a>
        </div>
    </div>

    <button class="c-nav-toggle" type="button" data-toggle="collapse" data-target="#main-nav">
        <span class="c-nav-toggle__bar"></span>
        <span class="c-nav-toggle__bar"></span>
        <span class="c-nav-toggle__bar"></span>
    </button><!-- // .c-nav-toggle -->
</header>
@yield ('content')
</body>

<script src="{{ asset('plugins/jquery/jquery-2.1.3.min.js') }}"></script>
<script href="{{ asset('dashboardui/js/main.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.dataTables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/jquery.dataTables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/restfulizer.js') }}"></script>
<script src="{{ asset('js/custom-scripts.js') }}"></script>
@stack('scripts')
</html>