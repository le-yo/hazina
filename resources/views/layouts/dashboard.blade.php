<!DOCTYPE html>
<html lang="en">
    @include('partials.landingheader')
    <body id="home">

    <nav class="navbar navbar-default" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">Payments</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-right navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ Request::is('/*') ? 'active' : '' }}"><a href="{{ route('home') }}">Dashboard</a></li>
                    {{--@if (Auth::check() && Auth::inRole('administrator'))--}}
                        {{--<li class="{{ Request::is('users*') ? 'active' : '' }}"><a href="{{ route('users.index') }}">Users</a></li>--}}
                        {{--<li class="{{ Request::is('roles*') ? 'active' : '' }}"><a href="{{ route('roles.index') }}">Roles</a></li>--}}
                    {{--@endif--}}
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::check())
                        {{--<li><a href="{{ route('categories') }}">Payment Categories</a></li>--}}
                        <li><a class="navbar-text">{{ Auth::getUser()->email }}</a></li>
                        <li>
                        <a href="{{ route('logout') }}"
                                                                    onclick="event.preventDefault();
                                                                             document.getElementById('logout-form').submit();">
                                                                    Logout
                                                                </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                                        {{ csrf_field() }}
                                                                    </form></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endif
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="container">
{{--    @include('Centaur::notifications')--}}
    @yield('content')
    </div>
    @include('partials.landingfooter')

</body>

</html>
