@extends('dashboardui.layouts.app')

@section('title', 'Payments')

@section('content')
	{{--<div class="page-header">--}}
		{{--<div class='btn-toolbar pull-right'>--}}
			{{--<a class="btn btn-info btn-lg" href="">--}}
				{{--<span class="icon-refresh" aria-hidden="true"></span>--}}
				{{--Refresh--}}
			{{--</a>--}}
		{{--</div>--}}
		{{--<h1><i class="icon-wallet"></i>&nbsp;Short Term Payments</h1>--}}
	{{--</div>--}}
	{{--<div class="page-header">--}}
		{{--<div class="pull-right">--}}
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
		{{--</div>--}}
		{{--<h3>Excel Upload</h3>--}}
	{{--</div>--}}
    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="panel panel-info">--}}
				{{--<div class="panel-heading">--}}
					{{--<h3 class="panel-title">Payments</h3>--}}
				{{--</div>--}}
				{{--<div class="panel-body">--}}
					{{--<div class="tabs">--}}
						{{--<ul class="nav nav-tabs">--}}
							{{--<li class="active">--}}
								{{--<a href="#unprocessed" role="tab" data-toggle="tab">--}}
									{{--<i class="icon-user-follow"></i> Unprocessed Payments--}}
								{{--</a>--}}
							{{--</li>--}}
							{{--<li>--}}
								{{--<a href="#processed" role="tab" data-toggle="tab" onclick="processedPaymentsDataTables()">--}}
									{{--<i class="icon-user-following"></i> Processed Payments--}}
								{{--</a>--}}
							{{--</li>--}}
							{{--<li>--}}
								{{--<a href="#unrecognized" role="tab" data-toggle="tab" onclick="unrecognizedPaymentsDataTables()">--}}
									{{--<i class="icon-user-unfollow"></i> Unrecognized Payments--}}
								{{--</a>--}}
							{{--</li>--}}
						{{--</ul>--}}
						{{--<div class="tab-content">--}}
							{{--<div class="tab-pane fade active in" id="unprocessed">--}}
								{{--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
									{{--<div class="table-responsive">--}}
										{{--<table class="table table-bordered table-condensed unprocessed-payments-table" id="unprocessed-payments-table" cellspacing="0" width="100%">--}}
											{{--<thead>--}}
											{{--<tr>--}}
												{{--<th>Id</th>--}}
												{{--<th>Phone</th>--}}
												{{--<th nowrap="">Client Name</th>--}}
												{{--<th>Trans. ID</th>--}}
												{{--<th>Account</th>--}}
												{{--<th>Amount</th>--}}
												{{--<th>Transaction Time</th>--}}
												{{--<th>Comment</th>--}}
												{{--<th>Actions</th>--}}
											{{--</tr>--}}
											{{--</thead>--}}
										{{--</table>--}}
									{{--</div>--}}
								{{--</div>--}}
							{{--</div>--}}
							{{--<div class="tab-pane fade" id="processed">--}}
								{{--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
									{{--<div class="table-responsive">--}}
										{{--<table class="table table-bordered table-condensed processed-payments-table" id="processed-payments-table" cellspacing="0" width="100%">--}}
											{{--<thead>--}}
											{{--<tr>--}}
												{{--<th>Id</th>--}}
												{{--<th>Phone</th>--}}
												{{--<th nowrap="">Client Name</th>--}}
												{{--<th>Trans. ID</th>--}}
												{{--<th>Account</th>--}}
												{{--<th>Amount</th>--}}
												{{--<th>Transaction Time</th>--}}
												{{--<th>Comment</th>--}}
												{{--<th>Actions</th>--}}
											{{--</tr>--}}
											{{--</thead>--}}
										{{--</table>--}}
									{{--</div>--}}
								{{--</div>--}}
							{{--</div>--}}
							{{--<div class="tab-pane fade" id="unrecognized">--}}
								{{--<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">--}}
									{{--<div class="table-responsive">--}}
										{{--<table class="table table-bordered table-condensed unrecognized-payments-table" id="unrecognized-payments-table" cellspacing="0" width="100%">--}}
											{{--<thead>--}}
											{{--<tr>--}}
												{{--<th>Id</th>--}}
												{{--<th>Phone</th>--}}
												{{--<th nowrap="">Client Name</th>--}}
												{{--<th>Trans. ID</th>--}}
												{{--<th>Account</th>--}}
												{{--<th>Amount</th>--}}
												{{--<th>Transaction Time</th>--}}
												{{--<th>Comment</th>--}}
												{{--<th>Actions</th>--}}
											{{--</tr>--}}
											{{--</thead>--}}
										{{--</table>--}}
									{{--</div>--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
					{{--</div>--}}
				{{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--New content--}}
    <div class="container">
        <div class="row u-mb-large">
            <div class="col-12">
                <div class="c-table-responsive@desktop">
                    <table class="c-table" id="datatable">
                        <caption class="c-table__title">
                            Sortable Tables <small>Powered by DataTables</small>
                        </caption>

                        <thead class="c-table__head c-table__head--slim">
                        <tr class="c-table__row">
                            <th class="c-table__cell c-table__cell--head no-sort">Project</th>
                            <th class="c-table__cell c-table__cell--head">Deadline</th>
                            <th class="c-table__cell c-table__cell--head no-sort">Leader + Team</th>
                            <th class="c-table__cell c-table__cell--head">Budget</th>
                            <th class="c-table__cell c-table__cell--head no-sort">Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr class="c-table__row">
                            <td class="c-table__cell">New Dashboard</td>
                            <td class="c-table__cell">17th Oct, 17</td>
                            <td class="c-table__cell">Mahmoud</td>
                            <td class="c-table__cell">$4,670</td>
                            <td class="c-table__cell">Finishing</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">landing Page</td>
                            <td class="c-table__cell">23th Dec, 18</td>
                            <td class="c-table__cell">Norman Hammond</td>
                            <td class="c-table__cell">$5,740</td>
                            <td class="c-table__cell">Early Stages</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">Customer Care Interface</td>
                            <td class="c-table__cell">2nd Jan, 18</td>
                            <td class="c-table__cell">Joseph Mullins</td>
                            <td class="c-table__cell">$4,000</td>
                            <td class="c-table__cell">QA</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">UX Consulting</td>
                            <td class="c-table__cell">1st Apr, 18</td>
                            <td class="c-table__cell">Ina Higgins</td>
                            <td class="c-table__cell">$2,500</td>
                            <td class="c-table__cell">Waiting for Client</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">Mongo DB Integration</td>
                            <td class="c-table__cell">23th Dec, 18</td>
                            <td class="c-table__cell">Stella Munoz</td>
                            <td class="c-table__cell">$800</td>
                            <td class="c-table__cell">Finishing</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">Small Design Help</td>
                            <td class="c-table__cell">10th Apr, 17</td>
                            <td class="c-table__cell">Dylan Shelton</td>
                            <td class="c-table__cell">$2,180</td>
                            <td class="c-table__cell">Waiting for Client</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">Subpages</td>
                            <td class="c-table__cell">1st Mar, 18</td>
                            <td class="c-table__cell">Ellen Santiago</td>
                            <td class="c-table__cell">$4,670</td>
                            <td class="c-table__cell">QA</td>
                        </tr>
                        <tr class="c-table__row">
                            <td class="c-table__cell">Analytics</td>
                            <td class="c-table__cell">1st Jan, 18</td>
                            <td class="c-table__cell">Maurice Briggs</td>
                            <td class="c-table__cell">$1350</td>
                            <td class="c-table__cell">Early Stages</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row u-mb-large">
        {{--<div class="col-md-12">--}}
            {{--<div class="c-tabs">--}}
                <ul class="c-tabs__list nav nav-tabs" id="myTab" role="tablist">
                    <li><a class="c-tabs__link active" id="nav-home-tab" data-toggle="tab" href="#unprocessed" role="tab" aria-controls="nav-home" aria-selected="true">Unprocessed</a></li>

                    <li><a class="c-tabs__link" id="nav-profile-tab" data-toggle="tab" href="#processed" role="tab" aria-controls="nav-profile" aria-selected="false" onclick="processedPaymentsDataTables();">Processed</a></li>

                    <li><a class="c-tabs__link" id="nav-contact-tab" data-toggle="tab" href="#unrecognized" role="tab" aria-controls="nav-contact" aria-selected="false" onclick="unrecognizedPaymentsDataTables();">Unrecognized</a></li>
                </ul>
                <div class="c-tabs__content tab-content" id="nav-tabContent">
                    <div class="c-tabs__pane active" id="unprocessed" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="c-table-responsive@desktop">
                            <table class="c-table unprocessed-payments-table" id="unprocessed-payments-table">
                                <caption class="c-table__title">
                                    Payments
                                </caption>
                                <thead class="c-table__head c-table__head--slim">
                                <tr class="c-table__row">
                                    <th class="c-table__cell c-table__cell--head">Id</th>
                                    <th class="c-table__cell c-table__cell--head">Phone</th>
                                    <th class="c-table__cell c-table__cell--head">Client Name</th>
                                    <th class="c-table__cell c-table__cell--head">Trans. ID</th>
                                    <th class="c-table__cell c-table__cell--head">Account</th>
                                    <th class="c-table__cell c-table__cell--head">Amount</th>
                                    <th class="c-table__cell c-table__cell--head">Transaction Time</th>
                                    <th class="c-table__cell c-table__cell--head">
                                        <span class="u-hidden-visually">Actions</span>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div><!-- // .c-card -->
                    </div>
                    <div class="c-tabs__pane" id="processed" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="c-tabs__pane active" id="processed" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="c-table-responsive@wide">
                                <table class="c-table processed-payments-table" id="processed-payments-table">
                                    <caption class="c-table__title">
                                        Payments
                                    </caption>
                                    <thead class="c-table__head c-table__head--slim">
                                    <tr class="c-table__row">
                                        <th class="c-table__cell c-table__cell--head">Id</th>
                                        <th class="c-table__cell c-table__cell--head">Phone</th>
                                        <th class="c-table__cell c-table__cell--head">Client Name</th>
                                        <th class="c-table__cell c-table__cell--head">Trans. ID</th>
                                        <th class="c-table__cell c-table__cell--head">Account</th>
                                        <th class="c-table__cell c-table__cell--head">Amount</th>
                                        <th class="c-table__cell c-table__cell--head">Transaction Time</th>
                                        <th class="c-table__cell c-table__cell--head">
                                            <span class="u-hidden-visually">Actions</span>
                                        </th>
                                    </tr>
                                    </thead>
                                </table>
                            </div><!-- // .c-card -->
                        </div>
                    </div>
                    <div class="c-tabs__pane" id="unrecognized" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="c-tabs__pane active" id="unrecognized" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="c-table-responsive@wide">
                                    <table class="c-table unrecognized-payments-table" id="unrecognized-payments-table">
                                        <caption class="c-table__title">
                                            Payments
                                        </caption>
                                        <thead class="c-table__head c-table__head--slim">
                                        <tr class="c-table__row">
                                            <th class="c-table__cell c-table__cell--head">Id</th>
                                            <th class="c-table__cell c-table__cell--head">Phone</th>
                                            <th class="c-table__cell c-table__cell--head">Client Name</th>
                                            <th class="c-table__cell c-table__cell--head">Trans. ID</th>
                                            <th class="c-table__cell c-table__cell--head">Account</th>
                                            <th class="c-table__cell c-table__cell--head">Amount</th>
                                            <th class="c-table__cell c-table__cell--head">Transaction Time</th>
                                            <th class="c-table__cell c-table__cell--head">
                                                <span class="u-hidden-visually">Actions</span>
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div><!-- // .c-card -->
                            </div>
                        </div>
                </div>
    {{--</div>--}}
    {{--</div>--}}
    </div>
    </div>
	<!-- Modal -->
	@include('partials.modal')
	<!-- End of Modal -->
@stop
@push('scripts')
    <style>
        .padded{
            padding-bottom: 30px;
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
			$('#unprocessed-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables', [STL_PAYBILL]) !!}',
				"order": [[0,'desc']],
				"lengthMenu": [[10,25,50], [10,25,50]],
				columns: [
					{data: 'id', name: 'id',sClass:"numericCol c-table__cell u-text-mute" },
					{data: 'phone', name: 'phone',sClass:"numericCol c-table__cell u-text-mute" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol c-table__cell display-name"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol c-table__cell"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol c-table__cell"},
					{data: 'amount', name: 'amount',sClass:"numericCol c-table__cell"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol c-table__cell"},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol c-table__cell u-text-right", searchable: false}
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
				"order": [[0, 'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
                columns: [
                    {data: 'id', name: 'id',sClass:"numericCol c-table__cell u-text-mute" },
                    {data: 'phone', name: 'phone',sClass:"numericCol c-table__cell u-text-mute" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol c-table__cell display-name"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol c-table__cell"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol c-table__cell"},
                    {data: 'amount', name: 'amount',sClass:"numericCol c-table__cell"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol c-table__cell"},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
                    {data: 'action', name: 'action',sClass:"numericCol c-table__cell u-text-right", searchable: false}
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
		}

		function unrecognizedPaymentsDataTables() {
            var $unrecognizedPaymentsTable = $('#unrecognized-payments-table');
			var table = $unrecognizedPaymentsTable.DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                footer:true,
                ajax: '{!! route('payments.datatables', [STL_PAYBILL]) !!}',
                "order": [[0,'desc']],
                "lengthMenu": [[10,25,50], [10,25,50]],
                columns: [
                    {data: 'id', name: 'id',sClass:"numericCol c-table__cell u-text-mute" },
                    {data: 'phone', name: 'phone',sClass:"numericCol c-table__cell u-text-mute" },
                    {data: 'client_name', name: 'client_name',sClass:"numericCol c-table__cell display-name"},
                    {data: 'transaction_id', name: 'transaction_id',sClass:"numericCol c-table__cell"},
                    {data: 'account_no', name: 'account_no',sClass:"numericCol c-table__cell"},
                    {data: 'amount', name: 'amount',sClass:"numericCol c-table__cell"},
                    {data: 'transaction_time', name: 'transaction_time',sClass:"numericCol c-table__cell"},
                    // {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
                    {data: 'action', name: 'action',sClass:"numericCol c-table__cell u-text-right", searchable: false}
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
		}
	</script>
@endpush