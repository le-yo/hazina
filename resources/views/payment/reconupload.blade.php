@extends('layouts.argon')
@section('title','Create')
@section('content')

    <section class="content container">
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <h1>
            &nbsp;Upload MPESA File
        </h1>
        <form method = 'POST' action = '{!!url("payments/upload")!!}' enctype="multipart/form-data">
            @if(count($errors)>0)
                @foreach($errors->messages() as $key=>$error)
                    <div class="alert alert-danger">
                        <strong>Error!</strong> {{$error[0]}}
                    </div>
                @endforeach
            @endif
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <ul>
                        <li>{!! \Session::get('success') !!}</li>
                    </ul>
                </div> 
            @endif
            @if (\Session::has('error'))
                <div class="alert alert-info">
                    <ul>
                        <li>{!! \Session::get('error') !!}</li>
                    </ul>
                </div>
            @endif
            @if(isset($message))
                <div class="alert alert-info">
                    <strong>Info</strong> {{$message}}
                </div>
            @endif
            {{ csrf_field() }}
                <div class="row col-md-6">
                    <div class="form-group col-md-12">
                        <label for="filebutton">Upload MPESA File for reconciliation. </label><br>
                        <input id="mpesa_xls" name="mpesa_xls" value="{{ old('mpesa_excel') }}" class="input-file" type="file" required>
                    </div>
                    <br>
                    <button class = 'btn btn-success pull-right' type ='submit'> <i class="fa"></i> Submit</button>
                </div>
        </form>
    </section>
@endsection