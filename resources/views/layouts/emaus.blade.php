<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hazina | Mifos Mobi</title>

    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">

    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700%7CLato:300,400,400i,700' rel='stylesheet'>

    <!-- Css -->
    <link rel="stylesheet" href="{{ asset('emaus/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('emaus/css/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('emaus/css/style.css') }}">
{{--<link rel="stylesheet" href="css/bootstrap.min.css" />--}}
{{--<link rel="stylesheet" href="css/font-icons.css" />--}}
{{--<link rel="stylesheet" href="css/style.css" />--}}

<!-- Favicons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

</head>
<body>

<!-- Preloader -->
<div class="loader-mask">
    <div class="loader">
        "Loading..."
    </div>
</div>

<main class="main-wrapper">

    <!-- Navigation -->
    <header class="nav nav--transparent">

        <div class="nav__holder" id="sticky-nav">
            <div class="container">
                <div class="flex-parent">

                    <div class="nav__header clearfix">
                        <!-- Logo -->
                        <div class="logo-wrap">
                            <a href="index.html" class="logo__link">
                                <!--Welcome to Hazina-->
                                <!--<img class="logo logo&#45;&#45;dark" src="img/logo_dark.png" alt="logo">-->
                                <!--<img class="logo logo&#45;&#45;light" src="img/logo_light.png" alt="logo">-->
                            </a>
                        </div>

                        <button type="button" class="nav__icon-toggle" id="nav__icon-toggle" data-toggle="collapse" data-target="#navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="nav__icon-toggle-bar"></span>
                            <span class="nav__icon-toggle-bar"></span>
                            <span class="nav__icon-toggle-bar"></span>
                        </button>
                    </div> <!-- end nav-header -->

                    <nav id="navbar-collapse" class="nav__wrap collapse navbar-collapse">
                        <!--<ul class="nav__menu nav__menu&#45;&#45;inline">-->

                        <!--<li class="nav__dropdown active">-->
                        <!--<a href="index.html">Home</a>-->
                        <!--<i class="ui-arrow-down nav__dropdown-trigger"></i>-->
                        <!--<ul class="nav__dropdown-menu">-->
                        <!--<li><a href="index.html">Saas Landing</a></li>-->
                        <!--<li><a href="index-2.html">Mobile App</a></li>-->
                        <!--<li><a href="index-3.html">eBook Landing</a></li>-->
                        <!--</ul>-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<a href="features.html">Features</a>-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<a href="pricing.html">Pricing</a>-->
                        <!--</li>-->
                        <!--<li class="nav__dropdown">-->
                        <!--<a href="blog.html">Blog</a>-->
                        <!--<i class="ui-arrow-down nav__dropdown-trigger"></i>-->
                        <!--<ul class="nav__dropdown-menu">-->
                        <!--<li><a href="blog.html">Blog Standard</a></li>-->
                        <!--<li><a href="blog-masonry.html">Blog Masonry</a></li>-->
                        <!--<li><a href="single-post.html">Single Post</a></li>-->
                        <!--</ul>-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<a href="icons.html">Icons</a>-->
                        <!--</li>-->
                        <!--<li>-->
                        <!--<a href="knowledge-base.html">Knowledge Base</a>-->
                        <!--</li>-->

                        <!--</ul> &lt;!&ndash; end menu &ndash;&gt;-->

                        <!--<div class="nav__btn-holder">-->
                        <!--<a href="#" class="btn btn&#45;&#45;sm btn&#45;&#45;color rounded"><span>Log in</span></a>-->
                        <!--</div>-->
                        @guest
                            {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                            {{--                            <li><a href="{{ route('register') }}">Register</a></li>--}}
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </nav> <!-- end nav-wrap -->

                </div> <!-- end flex-parent -->
            </div> <!-- end container -->

        </div>
    </header> <!-- end navigation -->
    <div id="app">

        @yield('content')
    </div>
</main>
    <!-- Scripts -->


<!-- jQuery Scripts -->

<script src="{{ asset('emaus/js/jquery.min.js') }}"></script>
<script src="{{ asset('emaus/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('emaus/js/plugins.js') }}"></script>
<script src="{{ asset('emaus/js/scripts.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
