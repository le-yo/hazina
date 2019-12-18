@extends('layouts.argon')

{{--@section('title', 'Payments')--}}

@section('content')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container">
            <div class="header-body">

            @include('partials.notify')
                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Tx Today</h5>
                                        <span class="h2 font-weight-bold mb-0">No: {{$today_count}}</span><br>
                                        <span class="h2 font-weight-bold mb-0">Value: {{$today_sum}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$today_count/1000}}%</span>
                                    <span class="text-nowrap">Utilization</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Tx This week</h5>
                                        <span class="h2 font-weight-bold mb-0">No: {{$week_count}}</span><br>
                                        <span class="h2 font-weight-bold mb-0">Value: {{$week_sum}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-info mr-2"><i class="fas fa-arrow-up"></i>{{$week_count/1000}}%</span>
                                    <span class="text-nowrap">Since last week</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Tx This Month</h5>
                                        <span class="h2 font-weight-bold mb-0">No: {{$month_count}}</span><br>
                                        <span class="h2 font-weight-bold mb-0">Value: {{$month_sum}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
                                    <span class="text-warning mr-2"><i class="fas fa-arrow-up"></i> {{$month_count/1000}}%</span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6">
                        <div class="card card-stats mb-4 mb-xl-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">Total</h5>
                                        <span class="h2 font-weight-bold mb-0">No: {{$to_date_count}}</span><br>
                                        <span class="h2 font-weight-bold mb-0">Value: {{$to_date_sum}}</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                            <i class="fas fa-percent"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-muted text-sm">
{{--                                    <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> {{$to_date_count/1000}}%</span>--}}
                                    <span class="text-nowrap">All time</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
	{{--<div class="page-header">--}}
		{{--<div class='btn-toolbar pull-right'>--}}
			{{--<a class="btn btn-info" href="">--}}
				{{--<span class="icon-refresh" aria-hidden="true"></span>--}}
				{{--Refresh--}}
			{{--</a>--}}
		{{--</div>--}}
		{{--<h3>Dashboard</h3>--}}
	{{--</div>--}}
	<div class="page-header">
		<div class="pull-right">
			{{--{!! Form::open(array('url'=>'payments/upload','method'=>'POST', 'files'=>true, 'class'=>'form-inline')) !!}--}}
				{{--<div class="col-md-12">--}}
					{{--<div class="col-xs-10">--}}
						{{--<span id="filename">Select your file</span>--}}
						{{--<label for="file-upload">Browse<input type="file" id="file-upload" name="xls"></label>--}}
					{{--</div>--}}

					{{--<div class="col-xs-2">--}}
						{{--<button type="submit" class="btn btn-info pull-right">Upload</button>--}}
					{{--</div>--}}
				{{--</div>--}}
			{{--{!! Form::close() !!}--}}
            <br>
            <br>
            <br>
		</div>
		<h3>Payments</h3>
	</div>

    <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#processed" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true" onclick="processedPaymentsDataTables()"><i class="ni ni-cloud-upload-96 mr-2"></i>Processed Payments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#unprocessed" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false" onclick="unprocessedPaymentsDataTables()"><i class="ni ni-bell-55 mr-2"></i>Unprocessed Payments</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#unrecognized" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false" onclick="unrecognizedPaymentsDataTables()"><i class="ni ni-calendar-grid-58 mr-2"></i>Unrecognized Payments</a>
            </li>
        </ul>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="processed" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                        <table class="table align-items-center processed-payments-table" id="processed-payments-table" cellspacing="0" width="100%">
                            <thead class="thead-light">
                            <tr>
                                {{--<th scope="col">Id</th>--}}
                                <th scope="col">Phone</th>
                                <th nowrap="" scope="col">Client Name</th>
                                <th scope="col">Trans. ID</th>
                                <th scope="col">Account</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Transaction Time</th>
                                {{--<th scope="col">Paybill</th>--}}
                                {{--<th scope="col">Comment</th>--}}
                            </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane flex-sm-fill fade" id="unprocessed" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                    <div class="col-md-12">
                        <div class="table-responsive">
                        <table class="table align-items-center unprocessed-payments-table" id="unprocessed-payments-table" cellspacing="0" width="100%">
                            <thead class="thead-light">
                            <tr>
                                {{--<th scope="col">Id</th>--}}
                                <th scope="col">Phone</th>
                                <th nowrap="" scope="col">Client Name</th>
                                <th scope="col">Trans. ID</th>
                                <th scope="col">Account</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Transaction Time</th>
                                {{--<th scope="col">Paybill</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade tab-responsive" id="unrecognized" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                        <table class="table align-items-center unrecognized-payments-table" id="unrecognized-payments-table" cellspacing="0" width="100%">
                            <thead class="thead-light">
                            <tr>
                                {{--<th scope="col">Id</th>--}}
                                <th scope="col">Phone</th>
                                <th nowrap="" scope="col">Client Name</th>
                                <th scope="col">Trans. ID</th>
                                <th scope="col">Account</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Transaction Time</th>
                                {{--<th scope="col">Paybill</th>--}}
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                        </table>
                        </div>
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
		$('#file-upload').change(function() {
			var filepath = this.value;
			var m = filepath.match(/([^\/\\]+)$/);
			var filename = m[1];
			$('#filename').html(filename);

		});
		$(document).ready(function () {
			$('#processed-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables.processed', [STL_PAYBILL]) !!}',
				"order": [[5,'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					// {data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol display-name"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    // {data: 'paybill', name: 'paybill',sClass:"numericCol"},
                    // {data: 'status', name: 'status',sClass:"numericCol", searchable: true},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i></i>'},
					// {data: 'action', name: 'action',sClass:"numericCol", searchable: false}
				],
                drawCallback: function(settings) {
				    // Access Datatables API methods
                	var $api = new $.fn.dataTable.Api(settings);

				    var $calculator = $('.calculator');

                    $calculator.on('mouseenter', function () {
                        var row = $api.row($(this).closest('tr')).data();
                        var $calc = $(this);
                        var phone_no = row.phone;
                        var amount_paid = parseInt(row.amount.replace(/\,/g, ''));
                        var loan_url = '{!! recipients_STL_URL !!}' + phone_no;

                        $.ajax({
                            url: loan_url,
                            dataType: 'jsonp',
                            jsonp: 'callback',
                            jsonpCallback: 'loanCallback',
                            success: function(data) {
                                var response = {};

                                if (data.success !== undefined)
                                {
                                    var loan = data.loan.loanBalance;
                                    var ext = ((amount_paid*0.1) - (loan*0.1))/(0.1-1);
                                    var num = amount_paid - ext;
                                    var reduction = roundDown(num, -1);
                                    var fee = amount_paid - reduction;

                                    response["loan"] = loan;
                                    response["amount"] = amount_paid;
                                    response["reduction"] = reduction;
                                    response["fee"] = fee;
                                } else {
                                    response["loan"] = 'Not Found';
                                    response["amount"] = amount_paid;
                                    response["reduction"] = 0;
                                    response["fee"] = 0;
                                }

                                $calc.popover({
                                    'html': 'true',
                                    'title': row.client_name,
                                    'trigger': 'click',
                                    'placement': 'top',
                                    'container': 'body',
                                    'content': function () {
                                        return '<p class="outstanding"><b>Outstanding Loan: </b>'+response.loan+'</p>'+
                                            '<p class="amount"><b>Amount Paid: </b>'+response.amount+'</p>'+
                                            '<p class="reduction"><b>Loan Reduction: </b>'+response.reduction+'</p>'+
                                            '<p class="extension"><b>Extension Fee: </b>'+response.fee+'</p>';
                                    }
                                });
                            }
                        });

                        function roundDown(number, decimals) {
                            decimals = decimals || 0;
                            return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
                        }
                    });

                    $('.comment').on('click', function(e) {
                        var url = $(this).attr('data-url');

                        $("#form").attr('action', url);
                        $("#modal-comment").modal('show');

                        e.preventDefault();
                    });
                }
            });

		});

        function processedPaymentsDataTables() {
            var $processedPaymentsTable = $('#processed-payments-table');
            var table = $processedPaymentsTable.DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('payments.datatables.processed', [PCL_PAYBILL]) !!}',
                "order": [[5, 'desc']],
                "lengthMenu": [[50, 25, 10], [50, 25, 10]],
                columns: [
                    // {data: 'id', name: 'id'},
                    {data: 'phone', name: 'phone',sClass:"numericCol" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol display-name"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol"},
                    {data: 'amount', name: 'amount',sClass:"numericCol"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    // {data: 'paybill', name: 'paybill',sClass:"numericCol"},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i></i>'},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
                    // {data: 'action', name: 'action',sClass:"numericCol", searchable: false}
                    // {data: 'status', name: 'status',sClass:"numericCol", searchable: true}
                ],
                drawCallback: function(settings) {
                    // Access Datatables API methods
                    var $api = new $.fn.dataTable.Api(settings);

                    var $calculator = $('.calculator');

                    $('.comment').on('click', function(e) {
                        var url = $(this).attr('data-url');

                        $("#form").attr('action', url);
                        $("#modal-comment").modal('show');

                        e.preventDefault();
                    });
                }
            });
        }

		function unprocessedPaymentsDataTables() {
            $('#unprocessed-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables.unprocessed', [PCL_PAYBILL]) !!}',
				"order": [[5, 'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					// {data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol display-name"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
					// {data: 'paybill', name: 'paybill',sClass:"numericCol"},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i></i>'},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol", searchable: false}
					// {data: 'status', name: 'status',sClass:"numericCol", searchable: true}
				],

                drawCallback: function(settings) {
                    // Access Datatables API methods
                    var $api = new $.fn.dataTable.Api(settings);

                    var $calculator = $('.calculator');

                    $calculator.on('mouseenter', function () {
                        var row = $api.row($(this).closest('tr')).data();
                        var $calc = $(this);
                        var phone_no = row.phone;
                        var amount_paid = parseInt(row.amount.replace(/\,/g, ''));
                        var loan_url = '{!! recipients_STL_URL !!}' + phone_no;

                        $.ajax({
                            url: loan_url,
                            dataType: 'jsonp',
                            jsonp: 'callback',
                            jsonpCallback: 'loanCallback',
                            success: function(data) {
                                var response = {};

                                if (data.success !== undefined)
                                {
                                    var loan = data.loan.loanBalance;
                                    var ext = ((amount_paid*0.1) - (loan*0.1))/(0.1-1);
                                    var num = amount_paid - ext;
                                    var reduction = roundDown(num, -1);
                                    var fee = amount_paid - reduction;

                                    response["loan"] = loan;
                                    response["amount"] = amount_paid;
                                    response["reduction"] = reduction;
                                    response["fee"] = fee;
                                } else {
                                    response["loan"] = 'Not Found';
                                    response["amount"] = amount_paid;
                                    response["reduction"] = 0;
                                    response["fee"] = 0;
                                }

                                $calc.popover({
                                    'html': 'true',
                                    'title': row.client_name,
                                    'trigger': 'click',
                                    'placement': 'top',
                                    'container': 'body',
                                    'content': function () {
                                        return '<p class="outstanding"><b>Outstanding Loan: </b>'+response.loan+'</p>'+
                                            '<p class="amount"><b>Amount Paid: </b>'+response.amount+'</p>'+
                                            '<p class="reduction"><b>Loan Reduction: </b>'+response.reduction+'</p>'+
                                            '<p class="extension"><b>Extension Fee: </b>'+response.fee+'</p>';
                                    }
                                });
                            }
                        });

                        function roundDown(number, decimals) {
                            decimals = decimals || 0;
                            return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
                        }
                    });

                    $('.comment').on('click', function(e) {
                        // alert('this');
                        var url = $(this).attr('data-url');

                        $("#form").attr('action', url);
                        // $("#modal-comment").modal('show');

                        e.preventDefault();
                    });
                }
			});
		}

		function unrecognizedPaymentsDataTables() {
			var table = $('#unrecognized-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables.unrecognized', [STL_PAYBILL]) !!}',
				"order": [[5, 'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					// {data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol display-name" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
                    {data: 'paybill', name: 'paybill',sClass:"numericCol"},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i></i>'},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol", searchable: false}
                    // {data: 'status', name: 'status',sClass:"numericCol", searchable: true}
				],
                drawCallback: function(settings) {
				    // Access Datatables API methods
                	var $api = new $.fn.dataTable.Api(settings);

				    var $calculator = $('.calculator');

                    $calculator.on('mouseenter', function () {
                        var row = $api.row($(this).closest('tr')).data();
                        var $calc = $(this);
                        var phone_no = row.phone;
                        var amount_paid = parseInt(row.amount.replace(/\,/g, ''));
                        var loan_url = '{!! recipients_STL_URL !!}' + phone_no;

                        $.ajax({
                            url: loan_url,
                            dataType: 'jsonp',
                            jsonp: 'callback',
                            jsonpCallback: 'loanCallback',
                            success: function(data) {
                                var response = {};

                                if (data.success !== undefined)
                                {
                                    var loan = data.loan.loanBalance;
                                    var ext = ((amount_paid*0.1) - (loan*0.1))/(0.1-1);
                                    var num = amount_paid - ext;
                                    var reduction = roundDown(num, -1);
                                    var fee = amount_paid - reduction;

                                    response["loan"] = loan;
                                    response["amount"] = amount_paid;
                                    response["reduction"] = reduction;
                                    response["fee"] = fee;
                                } else {
                                    response["loan"] = 'Not Found';
                                    response["amount"] = amount_paid;
                                    response["reduction"] = 0;
                                    response["fee"] = 0;
                                }

                                $calc.popover({
                                    'html': 'true',
                                    'title': row.client_name,
                                    'trigger': 'click',
                                    'placement': 'top',
                                    'container': 'body',
                                    'content': function () {
                                        return '<p class="outstanding"><b>Outstanding Loan: </b>'+response.loan+'</p>'+
                                            '<p class="amount"><b>Amount Paid: </b>'+response.amount+'</p>'+
                                            '<p class="reduction"><b>Loan Reduction: </b>'+response.reduction+'</p>'+
                                            '<p class="extension"><b>Extension Fee: </b>'+response.fee+'</p>';
                                    }
                                });
                            }
                        });

                        function roundDown(number, decimals) {
                            decimals = decimals || 0;
                            return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
                        }
                    });

                    $('.comment').on('click', function(e) {
                        alert('this');
                        var url = $(this).attr('data-url');

                        $("#form").attr('action', url);
                        $("#modal-comment").modal('show');

                        e.preventDefault();
                    });
                }
            });
		}
	</script>
@endpush