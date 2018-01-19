@extends('Centaur::layout')

@section('title', 'Dashboard')

@section('content')
	<div class="page-header">
		<div class='btn-toolbar pull-right'>
			<a class="btn btn-info btn-lg" href="{{ url('/') }}">
				<span class="icon-refresh" aria-hidden="true"></span>
				Refresh
			</a>
		</div>
		<h1>Payments Dashboard</h1>
	</div>
	<div class="page-header">
		<div class="pull-right">
			{{--@if (Sentinel::check() && Sentinel::getUser()->hasAccess('admin'))--}}
			<!-- /.table-responsive -->
				{!! Form::open(array('url'=>'payments/upload','method'=>'POST', 'files'=>true, 'class'=>'form-inline')) !!}
					<div class="col-md-12">
						<div class="col-xs-10">
							<span id="filename">Select your file</span>
							<label for="file-upload">Browse<input type="file" id="file-upload" name="xls"></label>
						</div>

						<div class="col-xs-2">
							<button type="submit" class="btn btn-info pull-right">Upload</button>
						</div>
					</div>
				{!! Form::close() !!}
			{{--@endif--}}
		</div>
		<h3>Excel Upload</h3>
	</div>
    <div class="row">
        <div class="col-md-12">
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
										<th>Client Name</th>
										<th>Transaction ID</th>
										<th>Account Number</th>
										<th>Amount</th>
										<th>Transaction Time</th>
										<th>Comment</th>
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
										<th>Client Name</th>
										<th>Transaction ID</th>
										<th>Account Number</th>
										<th>Amount</th>
										<th>Transaction Time</th>
										<th>Comment</th>
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
										<th>Client Name</th>
										<th>Transaction ID</th>
										<th>Account Number</th>
										<th>Amount</th>
										<th>Transaction Time</th>
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
					{data: 'comments', name: 'comments',sClass:"numericCol", defaultContent: '<i>None provided</i>'},
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
			})
			.end()
			.on('click', '.calculator', function(e) {
				var url = $(this).attr('data-url');
				$("#form").attr('action', url);
				$.ajax({
					url: url,
					dataType: 'json',
					success: function(payment)
					{
						var phone_no = payment.phone;
						var amount_paid = payment.amount;
						var name = payment.client_name;
						var loan_url = '{!! recipients_Loan_URL !!}' + phone_no;
						$('.drop-up').find('.calc-head-text').html('Loan Calculation for: '+name);
						$.ajax({
							url: loan_url,
							dataType: 'jsonp',
							jsonpCallback: 'loanCallback',
							success: function(data){
								processData(data);
							},
							error: function (xhr, textStatus, errorMessage) {
								$('.drop-up')
								.find('.outstanding-loan').html('Outstanding Loan: Loan Balance not found')
								.end()
								.find('.amount').html('Amount Paid: '+amount_paid)
								.end()
								.find('.loan-reduction').html('Loan Reduction: 0')
								.end()
								.find('.ext-fee').html('Extension Fee: 0');
							}
						});
						function roundDown(number, decimals) {
							decimals = decimals || 0;
							return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
						}
						function processData(data){
							var loan = data.loan.loanBalance;
							var ext = (amount_paid*0.1 - loan*0.1)/(0.1-1);
							var num = amount_paid - ext;
							var reduction = roundDown(num, -1);
							var fee = amount_paid - reduction;
							$('.drop-up')
							.find('.outstanding-loan').html('Outstanding Loan: '+loan)
							.end()
							.find('.amount').html('Amount Paid: '+amount_paid)
							.end()
							.find('.loan-reduction').html('Loan Reduction: '+reduction)
							.end()
							.find('.ext-fee').html('Extension Fee: '+fee);
						}

					}
				});
				e.preventDefault();
			});
		});

		function processedPaymentsDataTables() {
			$('#processed-payments-table').DataTable({
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
			})
			.end()
			.on('click', '.calculator', function(e) {
				var url = $(this).attr('data-url');
				$("#form").attr('action', url);
				$.ajax({
					url: url,
					dataType: 'json',
					success: function(payment)
					{
						var phone_no = payment.phone;
						var amount_paid = payment.amount;
						var name = payment.client_name;
						var loan_url = '{!! recipients_Loan_URL !!}' + phone_no;
						$('.drop-up').find('.calc-head-text').html('Loan Calculation for: '+name);
						$.ajax({
							url: loan_url,
							dataType: 'jsonp',
							jsonpCallback: 'loanCallback',
							success: function(data){
								processData(data);
							},
							error: function (xhr, textStatus, errorMessage) {
								$('.drop-up')
								.find('.outstanding-loan').html('Outstanding Loan: Loan Balance not found')
								.end()
								.find('.amount').html('Amount Paid: '+amount_paid)
								.end()
								.find('.loan-reduction').html('Loan Reduction: 0')
								.end()
								.find('.ext-fee').html('Extension Fee: 0');
							}
						});
						function roundDown(number, decimals) {
							decimals = decimals || 0;
							return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
						}
						function processData(data){
							var loan = data.loan.loanBalance;
							var ext = (amount_paid*0.1 - loan*0.1)/(0.1-1);
							var num = amount_paid - ext;
							var reduction = roundDown(num, -1);
							var fee = amount_paid - reduction;
							$('.drop-up')
							.find('.outstanding-loan').html('Outstanding Loan: '+loan)
							.end()
							.find('.amount').html('Amount Paid: '+amount_paid)
							.end()
							.find('.loan-reduction').html('Loan Reduction: '+reduction)
							.end()
							.find('.ext-fee').html('Extension Fee: '+fee);
						}

					}
				});
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
			})
			.end()
			.on('click', '.calculator', function(e) {
				var url = $(this).attr('data-url');
				$("#form").attr('action', url);
				$.ajax({
					url: url,
					dataType: 'json',
					success: function(payment)
					{
						var phone_no = payment.phone;
						var amount_paid = payment.amount;
						var name = payment.client_name;
						var loan_url = '{!! recipients_Loan_URL !!}' + phone_no;
						$('.drop-up').find('.calc-head-text').html('Loan Calculation for: '+name);
						$.ajax({
							url: loan_url,
							dataType: 'jsonp',
							jsonpCallback: 'loanCallback',
							success: function(data){
								processData(data);
							},
							error: function (xhr, textStatus, errorMessage) {
								$('.drop-up')
								.find('.outstanding-loan').html('Outstanding Loan: Loan Balance not found')
								.end()
								.find('.amount').html('Amount Paid: '+amount_paid)
								.end()
								.find('.loan-reduction').html('Loan Reduction: 0')
								.end()
								.find('.ext-fee').html('Extension Fee: 0');
							}
						});
						function roundDown(number, decimals) {
							decimals = decimals || 0;
							return ( Math.floor( number * Math.pow(10, decimals) ) / Math.pow(10, decimals) );
						}
						function processData(data){
							var loan = data.loan.loanBalance;
							var ext = (amount_paid*0.1 - loan*0.1)/(0.1-1);
							var num = amount_paid - ext;
							var reduction = roundDown(num, -1);
							var fee = amount_paid - reduction;
							$('.drop-up')
							.find('.outstanding-loan').html('Outstanding Loan: '+loan)
							.end()
							.find('.amount').html('Amount Paid: '+amount_paid)
							.end()
							.find('.loan-reduction').html('Loan Reduction: '+reduction)
							.end()
							.find('.ext-fee').html('Extension Fee: '+fee);
						}

					}
				});
				e.preventDefault();
			});
		}
	</script>
@endpush