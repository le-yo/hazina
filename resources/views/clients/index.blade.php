@extends('layouts.dashboard')
@section('title', 'Clients')
@section('content')
    <div class="page-header">
        <div class='btn-toolbar'>
            <form method="POST" action="payments/upload"  files="true" class="form-inline" enctype="multipart/form-data">
                {{--{!! ::open(array('url'=>'payments/upload','method'=>'POST', 'files'=>true, 'class'=>'form-inline')) !!}--}}
                <div class="row">
                    <div class="col-md-8">

                    </div>
                    <div class="col-md-2">
                        {{--<div class="col-xs-10">--}}
                        {{--<span id="filename">Select your file</span>--}}
                        <input type="file" id="file-upload" name="xls">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info pull-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a class="btn btn-info btn-lg" href="{{ url('/download') }}">
                <span class="icon-refresh" aria-hidden="true"></span>
                Download Payments
            </a>
        </div>
        <h3>Transactions</h3>
    </div>



    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">Today</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            {{--<i class="fa fa-comments fa-5x"></i>--}}
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$today_count}}</div>
                            <div>Ksh: {{$today_sum}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">This week</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            {{--<i class="fa fa-shopping-cart fa-5x"></i>--}}
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$week_count}}</div>
                            <div>Ksh: {{$week_sum}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">This month</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            {{--<i class="fa fa-support fa-5x"></i>--}}
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$month_count}}</div>
                            <div>Ksh: {{$month_sum}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">To date</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            {{--<i class="fa fa-support fa-5x"></i>--}}
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{$to_date_count}}</div>
                            <div>Ksh: {{$to_date_sum}}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Payments</h3>
            </div>
            <div class="panel-body">
                <div class="tabs">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#unprocessed" role="tab" data-toggle="tab">
                                <i class="icon-user-follow"></i> Unprocessed Payments
                            </a>
                        </li>
                        <li>
                            <a href="#processed" role="tab" data-toggle="tab" onclick="processedPaymentsDataTables()">
                                <i class="icon-user-following"></i> Processed Payments
                            </a>
                        </li>
                        <li>
                            <a href="#unrecognized" role="tab" data-toggle="tab" onclick="unrecognizedPaymentsDataTables()">
                                <i class="icon-user-unfollow"></i> Unrecognized Payments
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active in" id="unprocessed">
                            <table class="table table-bordered table-condensed" id="unprocessed-payments-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Phone Number</th>
                                    <th>Name</th>
                                    <th>Receipt</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Date/Time</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="processed">
                            <table class="table table-bordered table-condensed" id="processed-payments-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Phone Number</th>
                                    <th>Name</th>
                                    <th>Receipt</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Date/Time</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="unrecognized">
                            <table class="table table-bordered table-condensed" id="unrecognized-payments-table" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Phone Number</th>
                                    <th>Name</th>
                                    <th>Receipt</th>
                                    <th>Account</th>
                                    <th>Amount</th>
                                    <th>Date/Time</th>
                                    <th>Comment</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @include('partials.modal')
    <!-- End of Modal -->
@stop
@push('scripts')
    <style>
        .numericCol{
            text-align: right;
        }
    </style>
    <script>
        $(document).ready(function () {
            var mytable = $('#unprocessed-payments-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('payments.datatables') !!}',
                "order": [[0,'desc']],
                "lengthMenu": [[50, 25, 10], [50, 25, 10]],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'phone', name: 'phone',sClass:"numericCol" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol"},
                    {data: 'amount', name: 'amount',sClass:"numericCol"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    {data: 'category', name: 'category',sClass:"numericCol", defaultContent: '<i>None provided</i>'},
                    {data: 'action', name: 'action',sClass:"numericCol"}
                ]
            });

//			mytable.on('order.dt search.dt', function () {
//				mytable.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
//					cell.innerHTML = i + 1;
//				});
//			}).draw();

            $('#unprocessed-payments-table tbody').on('click', '.comment', function(e) {
                var url = $(this).attr('data-url');
                $("#form").attr('action', url);
                $("#modal-comment").modal('show');
                e.preventDefault();
            });

            $('#unprocessed-payments-table tbody').on('click', '.calculator', function(e) {
                var url = $(this).attr('data-url');
                var loan = $(this).attr('data-loan');
                var amount = $(this).attr('data-amount');
                var ext = $(this).attr('data-ext');
                var reduction = $(this).attr('data-reduction');
                var fee = $(this).attr('data-fee');
                $("#form").attr('action', url);
                $('input[name="loan"]').attr('value', loan);
                $('input[name="amount"]').attr('value', amount);
                $('input[name="new-ext"]').attr('value', ext);
                $('input[name="reduction"]').attr('value', reduction);
                $('input[name="fee"]').attr('value', fee);
                $("#modal-calculator").modal('show');
                e.preventDefault();
            });
        });

        function processedPaymentsDataTables() {
            $('#processed-payments-table').DataTable({
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('payments.datatables.processed') !!}',
                "order": [[0, 'desc']],
                "lengthMenu": [[50, 25, 10], [50, 25, 10]],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'phone', name: 'phone',sClass:"numericCol" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol"},
                    {data: 'amount', name: 'amount',sClass:"numericCol"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    {data: 'comments', name: 'comments',sClass:"numericCol", defaultContent: '<i>None provided</i>'},
                    {data: 'action', name: 'action',sClass:"numericCol"}
                ]
            });

            $('#processed-payments-table tbody').on('click', '.comment', function(e) {
                var url = $(this).attr('data-url');
                $("#form").attr('action', url);
                $("#modal-comment").modal('show');
                e.preventDefault();
            });

            $('#processed-payments-table tbody').on('click', '.calculator', function(e) {
                var url = $(this).attr('data-url');
                var loan = $(this).attr('data-loan');
                var amount = $(this).attr('data-amount');
                var ext = $(this).attr('data-ext');
                var reduction = $(this).attr('data-reduction');
                var fee = $(this).attr('data-fee');
                $("#form").attr('action', url);
                $('input[name="loan"]').attr('value', loan);
                $('input[name="amount"]').attr('value', amount);
                $('input[name="new-ext"]').attr('value', ext);
                $('input[name="reduction"]').attr('value', reduction);
                $('input[name="fee"]').attr('value', fee);
                $("#modal-calculator").modal('show');
                e.preventDefault();
            });
        }

        function unrecognizedPaymentsDataTables() {
            $('#unrecognized-payments-table').DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('payments.datatables.unrecognized') !!}',
                "order": [[0, 'desc']],
                "lengthMenu": [[50, 25, 10], [50, 25, 10]],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'phone', name: 'phone',sClass:"numericCol" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol"},
                    {data: 'amount', name: 'amount',sClass:"numericCol"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    {data: 'comments', name: 'comments',sClass:"numericCol", defaultContent: '<i>None provided</i>'},
                    {data: 'action', name: 'action',sClass:"numericCol"}
                ]
            });

            $('#unrecognized-payments-table tbody').on('click', '.comment', function(e) {
                var url = $(this).attr('data-url');
                $("#form").attr('action', url);
                $("#modal-comment").modal('show');
                e.preventDefault();
            });

            $('#unrecognized-payments-table tbody').on('click', '.calculator', function(e) {
                var url = $(this).attr('data-url');
                var loan = $(this).attr('data-loan');
                var amount = $(this).attr('data-amount');
                var ext = $(this).attr('data-ext');
                var reduction = $(this).attr('data-reduction');
                var fee = $(this).attr('data-fee');
                $("#form").attr('action', url);
                $('input[name="loan"]').attr('value', loan);
                $('input[name="amount"]').attr('value', amount);
                $('input[name="new-ext"]').attr('value', ext);
                $('input[name="reduction"]').attr('value', reduction);
                $('input[name="fee"]').attr('value', fee);
                $("#modal-calculator").modal('show');
                e.preventDefault();
            });
        }
    </script>
@endpush