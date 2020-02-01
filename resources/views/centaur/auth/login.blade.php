@extends('dashboardui.layouts.nosidebar')

@section('title', 'Login')

@section('content')
    <section id="landing-header">
    <div class="container">
        @include('Centaur::notifications')
        <div class="row guest-rw">
            <div class="col-md-4 "></div>
            <div class="col-md-4 ">
                <div class="logo">
                    <img src="/images/logo.png" class="login-logo img-responsive " alt="WatuCredit">
                </div>
                <div class="login">
                    <div class="login-triangle"></div>

                    <h2 class="login-header">Log in</h2>

                    <form class="login-container" accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.login.attempt') }}">

                        <p class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                            <input class="login-form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
                            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                        </p>
                        <p class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                            <input class="login-form-controls" placeholder="Password" name="password" type="password" value="">
                            {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                        </p>
                        <div class="checkbox">
                            {{--<label>--}}
                            {{--<input name="remember" type="checkbox" value="true" {{ old('remember') == 'true' ? 'checked' : ''}}> Remember Me--}}
                            {{--</label>--}}
                        </div>
                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                        <p><input  type="submit" value="Login"></p>
                        <p style="margin-top:5px; margin-bottom:0"><a href="{{ route('auth.password.request.form') }}" type="submit">Forgot your password?</a></p>

                    </form>
                </div>
            </div>
            <div class="col-md-4 ">
                {{--<div class="panel panel-info">--}}
                    {{--<div class="panel-heading watupanel">--}}
                        {{--<h3 class="panel-title">Login</h3>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.login.attempt') }}">--}}
                            {{--<fieldset>--}}
                                {{--<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">--}}
                                    {{--<input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">--}}
                                    {{--{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}--}}
                                {{--</div>--}}
                                {{--<div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">--}}
                                    {{--<input class="form-control" placeholder="Password" name="password" type="password" value="">--}}
                                    {{--{!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}--}}
                                {{--</div>--}}
                                {{--<div class="checkbox">--}}
                                    {{--<label>--}}
                                        {{--<input name="remember" type="checkbox" value="true" {{ old('remember') == 'true' ? 'checked' : ''}}> Remember Me--}}
                                    {{--</label>--}}
                                {{--</div>--}}
                                {{--<input name="_token" value="{{ csrf_token() }}" type="hidden">--}}
                                {{--<input class="btn btn-lg btn-info btn-block" type="submit" value="Login">--}}
                                {{--<p style="margin-top:5px; margin-bottom:0"><a href="{{ route('auth.password.request.form') }}" type="submit">Forgot your password?</a></p>--}}
                            {{--</fieldset>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
    </section>
@stop