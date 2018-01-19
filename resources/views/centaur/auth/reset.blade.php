@extends('Centaur::login-layout')

@section('title', 'Resend Activation Instructions')

@section('content')
    <div class="container">
        @include('Centaur::notifications')
        <div class="row guest-rw">
            {{--<div class="col-md-4 col-md-offset-4">--}}
                {{--<div class="panel panel-info">--}}
                    {{--<div class="panel-heading">--}}
                        {{--<h3 class="panel-title">Reset Your Passwords</h3>--}}
                    {{--</div>--}}
                    {{--<div class="panel-body">--}}
                        {{--<form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.password.request.attempt') }}">--}}
                            {{--<fieldset>--}}
                                {{--<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">--}}
                                    {{--<input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">--}}
                                    {{--{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}--}}
                                {{--</div>--}}
                                {{--<input name="_token" value="{{ csrf_token() }}" type="hidden">--}}
                                {{--<input class="btn btn-lg btn-info btn-block" type="submit" value="Help!">--}}
                            {{--</fieldset>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-md-4 "></div>
            <div class="col-md-4 ">
                <div class="logo">
                    <img src="/images/logo.png" class="login-logo img-responsive " alt="WatuCredit">
                </div>
                <div class="login">
                    <div class="login-triangle"></div>

                    <h2 class="login-header">Reset Your Password</h2>

                    <form class="login-container" accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.password.request.attempt') }}">

                        <p class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                            <input class="password-reset-form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
                            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                        </p>
                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                        <input class="" type="submit" value="Help!">

                    </form>
                </div>
            </div>
            <div class="col-md-4 "></div>
        </div>
    </div>
@stop