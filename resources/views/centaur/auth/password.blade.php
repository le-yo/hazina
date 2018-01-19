@extends('Centaur::login-layout')

@section('title', 'Create A New Password')

@section('content')
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

                    <h2 class="login-header">Reset Your Password</h2>

                    <form class="login-container" accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.password.reset.attempt', $code) }}">

                        <div class="form-group  {{ ($errors->has('password')) ? 'has-error' : '' }}">
                            <input class="password-form-control" placeholder="Password" name="password" type="password" value="">
                            {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                        </div>
                        <div class="form-group  {{ ($errors->has('password_confirmation')) ? 'has-error' : '' }}">
                            <input class="password-form-control" placeholder="Confirm Password" name="password_confirmation" type="password" value="">
                            {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                        </div>
                        <input name="_token" value="{{ csrf_token() }}" type="hidden">
                        <input class="btn btn-lg btn-info btn-block" type="submit" value="Save">

                    </form>
                </div>
            </div>
            <div class="col-md-4 "></div>

        </div>
    </div>
@stop