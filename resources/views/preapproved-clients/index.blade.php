@extends('layouts.dashboard')
@section('title', 'Clients')
@section('content')

    <section class="content">
        <h3>
            Preapproved clients
        </h3>
        <form class = 'col s3' method = 'get' action = '{!!url("preapproved-clients/upload")!!}'>
            <button class = 'btn btn-primary' type = 'submit'>Upload Clients</button>
        </form>
        <br>
        <br>
        <table class = "table table-striped table-bordered table-hover" style = 'background:#fff'>
            <thead>
            <th>Id</th>
            <th>Mobile Number</th>
            <th>ID Number</th>
            <th>Names</th>
            <th>Net Salary</th>
            <th>Loan limit</th>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{!!$client->id!!}</td>
                    <td>{!!$client->mobile_number!!}</td>
                    <td>{!!$client->national_id_number!!}</td>
                    <td>{!!$client->names!!}</td>
                    <td>{!!$client->net_salary!!}</td>
                    <td>{!!$client->loan_limits!!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {!! $clients->render() !!}
    </section>
@endsection