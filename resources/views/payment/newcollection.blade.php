@extends('dashboardui.layouts.nosidebar')
@section('title', 'Payments')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endpush

@section('content')

        <div class="u-mb-medium" style="padding: 20px;">
            <div class="c-tabs__content tab-content" id="nav-tabContent">
                <div class="c-tabs__pane active" id="unprocessed" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="panel panel-info c-table-responsive@desktop">
                <caption class="c-table__title">
                    Collection Sheet
                </caption>
                {{--<a href='{!!url("member")!!}/create' class = 'btn btn-success'><i class="fa fa-plus"></i> New</a>--}}
                <table id="searchData" class = "c-table">
                    <thead class="c-table__head c-table__head--slim">
                    <tr class="c-table__row">
                    </tr>
                    <th class="c-table__cell c-table__cell--head">Borrower Name</th>
                    <th class="c-table__cell c-table__cell--head" colspan="{!! count($collectionSheet->loanProducts) !!}">Due Collections</th>
                    <th class="c-table__cell c-table__cell--head" colspan="{!! count($collectionSheet->savingsProducts) !!}">Due Savings Collections</th>
                    <th class="c-table__cell c-table__cell--head">Attendance</th>
                    </thead>
                    <thead>
                    <th class="c-table__cell c-table__cell--head>Groups">Clients</th>
                    @foreach ($collectionSheet->loanProducts as $lp)
                        <th class="c-table__cell c-table__cell--head">{!! $lp->name !!}/Charges</th>
                    @endforeach
                    @foreach ($collectionSheet->savingsProducts as $sp)
                        <th class="c-table__cell c-table__cell--head">{!! $sp->name !!} (Saving Deposit)</th>
                    @endforeach
                    <th class="c-table__cell c-table__cell--head">Attendance</th>
                    </thead>
                    <tbody>

            @foreach($collectionSheet->groups as $group)
                            <tr class="c-table__row">
                            <th class="c-table__cell" colspan="{!! count($collectionSheet->loanProducts)+count($collectionSheet->savingsProducts)+2 !!}">{!! $group->groupName !!}</th>
                            </tr>
                        @foreach($group->clients as $client)
                            <tr>
                                <td class="c-table__cell">({!! $client->clientId !!}) {!! $client->clientName !!}</td>
                                @foreach ($collectionSheet->loanProducts as $lp)
                                    @if(isset($final_array[$group->groupId][$client->clientId]['loan'][$lp->id]->totalDue))
                                        <td class="c-table__cell"><a href="#" class="edittable {!! 'loan_'.$group->groupId.'_'.$lp->id !!}" id="{!! 'loan_'.$group->groupId.'_'.$client->clientId.'_'.$lp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['loan'][$lp->id]->totalDue !!}</a></td>
                                    @else
                                        <td class="c-table__cell"><a href="#" class="edittable {!! 'loan_'.$group->groupId.'_'.$lp->id !!}" id="{!! 'loan_'.$group->groupId.'_'.$client->clientId.'_'.$lp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                    @endif
                                @endforeach
                                @foreach ($collectionSheet->savingsProducts as $sp)
                                    @if(isset($final_array[$group->groupId][$client->clientId]['savings'][$sp->id]->dueAmount))
                                        <td class="c-table__cell"><a href="#" class="edittable {!! 'savings_'.$group->groupId.'_'.$sp->id !!}" id="{!! 'savings_'.$group->groupId.'_'.$client->clientId.'_'.$sp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['savings'][$sp->id]->dueAmount !!}</a></td>
                                    @else
                                        <td class="c-table__cell"><a href="#" class="edittable {!! 'savings_'.$group->groupId.'_'.$sp->id !!}" id="{!! 'savings_'.$group->groupId.'_'.$client->clientId.'_'.$sp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                    @endif
                                @endforeach

                                <td><select class="custom-select">
                                        @foreach($collectionSheet->attendanceTypeOptions as $attendanceTypeOption)
                                            @if($attendanceTypeOption->id==1)
                                            <option selected>{!! $attendanceTypeOption->value !!}</option>
                                            @else
                                            <option>{!! $attendanceTypeOption->value !!}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                         @endforeach
                        <tr>
                            <th class="c-table__cell c-table__cell--head">Group Total</th>
                            @foreach ($collectionSheet->loanProducts as $lp)
                                @if(isset($sum[$group->groupId]['loan'][$lp->id]))
                                    <th class="c-table__cell c-table__cell--head {!! 'loan_sum_'.$group->groupId.'_'.$lp->id !!}">{!! number_format($sum[$group->groupId]['loan'][$lp->id],2) !!}</th>
                                @else
                                    <th class="{!! 'loan_sum_'.$group->groupId.'_'.$lp->id !!}">0</th>
                                @endif
                            @endforeach
                            @foreach ($collectionSheet->savingsProducts as $sp)
                                @if(isset($sum[$group->groupId]['savings'][$sp->id]))
                                    <th class="c-table__cell c-table__cell--head {!! 'savings_sum_'.$group->groupId.'_'.$sp->id !!}">{!! number_format($sum[$group->groupId]['savings'][$sp->id],2) !!}</th>
                                @else
                                    <th class="c-table__cell c-table__cell--head {!! 'loan_sum_'.$group->groupId.'_'.$lp->id !!}">0</th>
                                @endif
                            @endforeach

                            {{--</td>--}}
                        </tr>
            @endforeach
            <tr>
                <td class="c-table__cell c-table__cell--head">Grand Total</td>
                @foreach ($collectionSheet->loanProducts as $lp)
                    @if(isset($totals_sum['loan'][$lp->id]))
                        <td class="c-table__cell c-table__cell--head">{!! number_format($totals_sum['loan'][$lp->id],2) !!}</td>
                    @else
                        <td class="c-table__cell c-table__cell--head">0</td>
                    @endif
                @endforeach
                @foreach ($collectionSheet->savingsProducts as $sp)
                    @if(isset($totals_sum['savings'][$sp->id]))
                        <td class="c-table__cell c-table__cell--head">{!! $totals_sum['savings'][$sp->id] !!}</td>
                    @else
                        <td class="c-table__cell c-table__cell--head">0</td>
                    @endif
                @endforeach
                <td>
                </td>
            </tr>

            <div class="panel-body">
                <h4>Total Due Collections: </h4> <h4 class="panel-title total_due" id="total_due">{!! $total_due !!}</h4>
            </div>
            <div class="panel-body">
                <h4>Total Payment made:</h4> <h4 class="panel-title" id="total_payment">{!! $payment->amount !!}</h4>
            </div>
            <div class="panel-body">
                <button type="button" id="submit_payments" class="btn btn-success">Submit Payments</button>
            </div>
            <br>
                    </tbody>
                </table>
                </div>

                </div>
            </div>
    </div>
	<!-- Modal -->
	{{--@include('partials.modal')--}}
	<!-- End of Modal -->
@stop
@push('scripts')

    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
                                            <script>
                                                $('.edittable').on("shown", function() {

                                                    $(this).data('editable').input.$input.on('keydown', function(e) {
                                                        // When TAB key is pressed
                                                        if (e.which == 9) {
                                                            e.preventDefault();
                                                            var that = this;
                                                            checkPayments();
                                                            calculateTotal();
                                                            // $(".editable:visible").eq($(".editable:visible").index(that) + 1).editable('show');

                                                            var eq_column = $(this).closest('td').index();
                                                            var eq_row = $(this).closest('tr').index();
                                                            var max_eq_row = $(this).closest('table').find('tr:last').index();

                                                            // SHIFT + TAB
                                                            if (e.shiftKey) {
                                                                if (eq_row != 0) {
                                                                    $(this)
                                                                        .blur()
                                                                        .closest('tr').prev()
                                                                        .find('td').eq(eq_column)
                                                                        .find(".editable").editable('show');
                                                                }
                                                                // Just TAB
                                                            } else {
                                                                if (eq_row != max_eq_row) {
                                                                    $(this)
                                                                        .blur()
                                                                        .closest('tr').next()
                                                                        .find('td').eq(eq_column)
                                                                        .find(".editable").editable('show');
                                                                }
                                                            }
                                                        }
                                                    });
                                                });
                                                checkPayments();
                                                calculateTotal();
                                                $('.edittable').editable({
                                                    type: 'text',
                                                    mode:'inline',
                                                    url: '/collectionSheetPost',
                                                    title: 'Enter new value',
                                                });
                                                $('.editable').on('hidden', function(e, reason){
                                                    if(reason === 'save' || reason === 'nochange') {
                                                        var that = this;
                                                        checkPayments();
                                                        calculateTotal();
                                                        $(".editable:visible").eq($(".editable:visible").index(that) + 1).editable('show');
                                                    }
                                                });
                                                function checkPayments(){
                                                    var payment = document.getElementById("total_payment").innerText;
                                                    // alert(payment);
                                                    var total_due = document.getElementById("total_due").innerText;
                                                    // alert(total_due);
                                                    if(Number(payment) == Number(total_due)){
                                                        document.getElementById("submit_payments").disabled = false;
                                                    }else{
                                                        document.getElementById("submit_payments").disabled = true;
                                                    }
                                                }
                                                function calculateTotal(){
                                                    var sum = 0;
                                                    var obj = {
                                                    };

                                                    $(".edittable").each(function() {
                                                        var val = $.trim( $(this).text() );
                                                        // alert(val);
                                                        if ( val ) {
                                                            val = parseFloat( val.replace( /^\$/, "" ) );

                                                            sum += !isNaN( val ) ? val : 0;
                                                        }
                                                    });
                                                    document.getElementById('total_due').innerHTML=sum;
                                                }

                                            </script>

@endpush