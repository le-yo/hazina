@extends('dashboardui.layouts.app')

@section('title', 'Payments')
@section('content')

<div class="container">
    <div class="col-md-10">
        <div class="col-md-12 c-alert c-alert--success alert" id="success-div">
            <i class="c-alert__icon fa fa-check-circle"></i>
            <button class="c-close" data-dismiss="alert" type="button">&times;</button>
        </div>
        <div class="col-md-12 c-alert c-alert--warning alert" id="error-div">
            <i class="c-alert__icon fa fa-exclamation-circle"></i>
            <button class="c-close" data-dismiss="alert" type="button">&times;</button>
        </div>

        </div>
        <p class="u-text-mute u-text-uppercase u-mb-medium">Upload M-PESA Statement</p>

        {!! Form::open(array('url'=>'payments/upload','method'=>'POST', 'class'=>'dropzone','files'=>true,'id'=>'reconciliation')) !!}

        <div id="dZUpload" class="">

        <div class="dz-message" id="my-dropzone" data-dz-message>
            <i class="dz-icon fa fa-cloud-upload"></i>
            <span>Drag a file here or browse for a file to upload.</span>
        </div>
        <div class="fallback">
            <input name="xls" type="file" multiple>
        </div>
        <br>


        </div>

    </div>
    <div class="container">
    <div class="col-md-2">
        <br>
        <button type="submit" id="button" class="c-btn c-btn--success float-left">Upload</button>
        <br>
    </div>
    </div>

        {!! Form::close() !!}
</div>

 @endsection

@section('scripts')
    <script>
        $("#error-div").hide();
        $("#success-div").hide();
        Dropzone.options.reconciliation = {
            autoProcessQueue: false,
            url: '/payments/upload',
            init: function () {

                var myDropzone = this;

                // Update selector to match your button
                $("#button").click(function (e) {
                    e.preventDefault();
                    myDropzone.processQueue();
                });

                this.on('sending', function(file, xhr, formData) {
                    // Append all form inputs to the formData Dropzone will POST
                    var data = $('#reconciliation').serializeArray();
                    $.each(data, function(key, el) {
                        formData.append(el.name, el.value);
                    });
                });
                this.on("success", function(file, responseText) {
                    var response = JSON.parse(responseText);
                    // if (data.responseText !== '') {
                    //
                    // }
                    // $("#msg").fadeOut(2000);
                    if (response.hasOwnProperty('error')) {
                    $("#error-div").show();
                    $("#error-div").html(response.error);
                    console.log(response.error);
                    }else{
                        $("#success-div").show();
                        $("#success-div").html(response.success);
                        console.log(response.success);
                    }
                });
            }
        }
    </script>
    @endsection