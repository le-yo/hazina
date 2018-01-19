@extends('Centaur::guest-layout')

@section('title', 'Resend Activation Instructions')

@section('content')
    <div class="container">
        @include('Centaur::notifications')
        <div class="row guest-rw">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Resend Activation Instructions</h3>
                    </div>
                    <div class="panel-body">
                        <form accept-charset="UTF-8" role="form" method="post" action="{{ route('auth.activation.resend') }}">
                            <fieldset>
                                <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
                                    <input class="form-control" placeholder="E-mail" name="email" type="text" value="{{ old('email') }}">
                                    {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                                </div>
                                <input name="_token" value="{{ csrf_token() }}" type="hidden">
                                <input class="btn btn-lg btn-info btn-block" type="submit" value="Send">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop