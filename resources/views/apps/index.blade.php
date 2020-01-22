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
    <div class="col-lg-8">
        <div class="c-card u-mb-medium" data-mh="invoices-cards">
            <div class="c-card__header c-card__header--transparent o-line u-border-bottom-zero">
                <h3 class="c-card__title">Monthly Summary
                    <span class="u-block u-text-mute u-text-xsmall">
                                        February 2017 (12 Days Remaining)
                                    </span>
                </h3>

                <div class="c-card__meta u-relative">
                    <a class="dropdown-toggle" href="#" id="dropdwonMenuCard1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="u-text-mute u-opacity-heavy fa fa-ellipsis-h"></i>
                    </a>

                    <div class="c-dropdown__menu dropdown-menu dropdown-menu-right fade" aria-labelledby="dropdwonMenuCard1">
                        <a class="c-dropdown__item" href="#">Link 1</a>
                        <a class="c-dropdown__item" href="#">Link 2</a>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="c-summary has-divider">
                        <h5 class="c-summary__title">Invoiced</h5>
                        <h5 class="c-summary__number">$2,190
                            <span class="c-summary__status u-bg-success">
                                                <i class="fa fa-caret-up"></i>
                                            </span>
                        </h5>
                        <p class="c-summary__meta">Last Month: $2,890</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="c-summary has-divider">
                        <h5 class="c-summary__title">Profit</h5>
                        <h5 class="c-summary__number">$2,190
                            <span class="c-summary__status u-bg-danger">
                                                <i class="fa fa-caret-down"></i>
                                            </span>
                        </h5>
                        <p class="c-summary__meta">Last Month: $2,890</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="c-summary">
                        <h5 class="c-summary__title">Expenses</h5>
                        <h5 class="c-summary__number">$2,190
                            <span class="c-summary__status u-bg-success">
                                                <i class="fa fa-caret-up"></i>
                                            </span>
                        </h5>
                        <p class="c-summary__meta">Last Month: $2,890</p>
                    </div>
                </div>
            </div><!-- // .row -->
        </div><!-- // .c-card -->
    </div>

    <div class="col-lg-4">
        <div class="c-card u-p-medium u-mb-medium" data-mh="invoices-cards">

            <div class="o-line">
                <h4 class="c-card__title">Percentual Profit
                    <span class="u-block u-text-mute u-text-xsmall">Quarterly (3 Months)</span>
                </h4>

                <div class="c-card__meta">
                    <a class="dropdown-toggle" href="#" id="dropdwonMenuCard2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="u-text-mute u-opacity-heavy fa fa-ellipsis-h"></i>
                    </a>

                    <div class="c-dropdown__menu dropdown-menu dropdown-menu-right fade" aria-labelledby="dropdwonMenuCard2">
                        <a class="c-dropdown__item" href="#">Link 1</a>
                        <a class="c-dropdown__item" href="#">Link 2</a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q1" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>
                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q2" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>

                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q3" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>

                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q4" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>

                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q5" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>

                <div class="col-2 u-pr-zero">
                    <div class="c-chart-container">
                        <canvas id="js-chart-profit-q6" height="85"></canvas>
                    </div>
                    <h5 class="c-chart__title">Q2 - 17</h5>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="row u-mb-large">
        <div class="col-md-12">
            <div class="c-table-responsive@wide">
                <table class="c-table">
                    <caption class="c-table__title">
                        Invoices
                    </caption>
                    <thead class="c-table__head c-table__head--slim">
                    <tr class="c-table__row">
                        <th class="c-table__cell c-table__cell--head">No.</th>
                        <th class="c-table__cell c-table__cell--head">Invoice Subject</th>
                        <th class="c-table__cell c-table__cell--head">Client</th>
                        <th class="c-table__cell c-table__cell--head">VAT No.</th>
                        <th class="c-table__cell c-table__cell--head">Created</th>
                        <th class="c-table__cell c-table__cell--head">Status</th>
                        <th class="c-table__cell c-table__cell--head">Price</th>
                        <th class="c-table__cell c-table__cell--head">
                            <span class="u-hidden-visually">Actions</span>
                        </th>
                        <th class="c-table__cell c-table__cell--head">
                            <span class="u-hidden-visually">Download</span>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Design Works</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Carlson Limited</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">15 Dec 2017</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--success">Paid</span>
                        </td>
                        <td class="c-table__cell">$887</td>

                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton10" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton10">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">UX Wireframes</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Adobe</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956421</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">12 Apr 2017</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--warning">Pending</span>
                        </td>
                        <td class="c-table__cell">$1200</td>
                        <td class="c-table__cell  u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton20" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton20">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00452</span></td>
                        <td class="c-table__cell">New Dashboard</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Bluewolf</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87952621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">23 Oct 2017</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--warning">Pending</span>
                        </td>
                        <td class="c-table__cell">$534</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton30" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton30">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Landing Page</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Salesforce</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87953421</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">2 Sep 2017</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--secondary">Due in 2 Weeks</span>
                        </td>
                        <td class="c-table__cell">$1500</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton40" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton40">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Marketing Templates</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Printic</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">29 Jan 2018</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--danger">Paid Today</span>
                        </td>
                        <td class="c-table__cell">$648</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton50" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton50">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Sales Presentation</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Tabdaq</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">4 Feb 2018</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--secondary">Due in 3 Weeks</span>
                        </td>
                        <td class="c-table__cell">$300</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton60" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton60">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Logo & Print</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Apple</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">22 Mar 2018</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--success">Paid Today</span>
                        </td>
                        <td class="c-table__cell">$2500</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton70" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton70">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->

                    <tr class="c-table__row">
                        <td class="c-table__cell"><span class="u-text-mute">00450</span></td>
                        <td class="c-table__cell">Icons</td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">Tookapic</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">87956621</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="u-text-mute">13 May 2018</span>
                        </td>
                        <td class="c-table__cell">
                            <span class="c-badge c-badge--small c-badge--success">Paid Today</span>
                        </td>
                        <td class="c-table__cell">$940</td>
                        <td class="c-table__cell u-text-right">
                            <a href="invoice.html" class="c-btn c-btn--info u-mr-xsmall">Manage</a>
                            <div class="c-dropdown dropdown">
                                <button class="c-btn c-btn--secondary has-dropdown dropdown-toggle" id="dropdownMenuButton80" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>

                                <div class="c-dropdown__menu dropdown-menu" aria-labelledby="dropdownMenuButton80">
                                    <a class="c-dropdown__item dropdown-item" href="#">Complete</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Share</a>
                                    <a class="c-dropdown__item dropdown-item" href="#">Archive</a>
                                </div>
                            </div>
                        </td>
                        <td class="c-table__cell">
                            <a class="u-text-mute" href="invoice.html">
                                <i class="fa fa-cloud-download"></i>
                            </a>
                        </td>
                    </tr><!-- // .table__row -->
                    </tbody>
                </table>
            </div><!-- // .c-card -->
        </div>
    </div><!-- // .row -->
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
			$('#unprocessed-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables', [STL_PAYBILL]) !!}',
				"order": [[0,'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					{data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol display-name"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol", searchable: false}
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
				"order": [[0, 'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					{data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol display-name"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol", searchable: false}
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
			var table = $('#unrecognized-payments-table').DataTable({
				destroy: true,
				processing: true,
				serverSide: true,
				ajax: '{!! route('payments.datatables.unrecognized', [PCL_PAYBILL]) !!}',
				"order": [[0, 'desc']],
				"lengthMenu": [[50, 25, 10], [50, 25, 10]],
				columns: [
					{data: 'id', name: 'id'},
					{data: 'phone', name: 'phone',sClass:"numericCol display-name" },
					{data: 'client_name', name: 'client_name',sClass:"numericCol"},
					{data: 'transaction_id', name: 'transaction_id',sClass:"numericCol"},
					{data: 'account_no', name: 'account_no',sClass:"numericCol"},
					{data: 'amount', name: 'amount',sClass:"numericCol"},
					{data: 'transaction_time', name: 'transaction_time',sClass:"numericCol"},
					// {data: 'comments', name: 'comments',sClass:"numericCol display-comment", defaultContent: '<i>None provided</i>'},
					{data: 'action', name: 'action',sClass:"numericCol", searchable: false}
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