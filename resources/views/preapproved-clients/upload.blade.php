@extends('layouts.dashboard')

@section('content')
    <section class="content">
        <h3>
          Upload Preapproved clients
        </h3>

<div class="container">
    <br>
    <form action="{{ url('preapproved-clients/upload') }}" method="post" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        Click Choose File :
        <input type="file" name="file" class="form-group">

        <input type="submit" class="btn btn-primary" value="Upload file">

    </form>
</div>
    </section>

@stop