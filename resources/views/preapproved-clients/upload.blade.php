@extends('layouts.app')

@section('content')

    {{--@if ( \Illuminate\Support\Facades\Session::has('success') )--}}
        {{--<div class="alert alert-success alert-dismissible" role="alert">--}}
            {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--<span class="sr-only">Close</span>--}}
            {{--</button>--}}
            {{--<strong>{{ \Illuminate\Support\Facades\Session::get('success') }}</strong>--}}
        {{--</div>--}}
    {{--@endif--}}

    {{--@if ( \Illuminate\Support\Facades\Session::has('error') )--}}
        {{--<div class="alert alert-danger alert-dismissible" role="alert">--}}
            {{--<button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
                {{--<span aria-hidden="true">×</span>--}}
                {{--<span class="sr-only">Close</span>--}}
            {{--</button>--}}
            {{--<strong>{{ \Illuminate\Support\Facades\Session::get('error') }}</strong>--}}
        {{--</div>--}}
    {{--@endif--}}

    {{--@if (count($errors) > 0)--}}
        {{--<div class="alert alert-danger">--}}
            {{--<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>--}}
            {{--<div>--}}
                {{--@foreach ($errors->all() as $error)--}}
                    {{--<p>{{ $error }}</p>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endif--}}

<div class="container">
    <form action="{{ url('preapproved-clients/upload') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        Click Choose File :
        <input type="file" name="file" class="form-group">

        <input type="submit" class="btn btn-primary">

    </form>
</div>

@stop