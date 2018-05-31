@if(session('success'))
<div class="row">
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Success:</strong> {!! session('success') !!}
    </div>
</div>
<?php Session::forget('success');?>
@endif

@if(session('error'))
<div class="row">
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Error:</strong> {!! session('error') !!}
    </div>
</div>
<?php Session::forget('error'); ?>
@endif

@if(session('warning'))
<div class="row">
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Warning:</strong> {!! $message !!}
    </div>
</div>
<?php Session::forget('warning'); ?>
@endif

@if(session('info'))
<div class="row">
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>FYI:</strong> {!! $message !!}
    </div>
</div>
<?php Session::forget('info'); ?>
@endif

<div class="row">
    <div id="loans-alert" class="alert-danger alert-dismissable" style="display: none">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" onclick="$('#loans-alert').removeClass('alert').hide();"><img src="{{ asset('images/close.gif') }}" alt="close button" class="img-responsive"/></button>
        <strong>Error:</strong> Select at least one loan to disburse
    </div>
</div>