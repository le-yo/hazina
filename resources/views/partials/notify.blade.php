{{--<div class="bg-gradient-primary pb-8 pt-5 pt-md-8">--}}
    {{--<div class="container">--}}
        {{--<div class="header-body">--}}
            <!-- Card stats -->
            {{--<div class="row">--}}
                {{--<div class="col-xl-3 col-lg-6">--}}
@if(session('success'))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Success:</strong> {!! session('success') !!}
    </div>
<?php Session::forget('success');?>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Error:</strong> {!! session('error') !!}
    </div>
<?php Session::forget('error'); ?>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Warning:</strong> {!! $message !!}
    </div>
<?php Session::forget('warning'); ?>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>FYI:</strong> {!! $message !!}
    </div>
<?php Session::forget('info'); ?>
@endif
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}