@extends('centaur.layout')

@section('title', 'Payments')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"></h3>
            </div>
            <div class="panel-body">
                Collection Sheet
            </div>
                <br>
                <br>
            </div>
            <div class="panel panel-info">

                {{--<a href='{!!url("member")!!}/create' class = 'btn btn-success'><i class="fa fa-plus"></i> New</a>--}}
                <table id="searchData" class = "table table-bordered table-condensed" style = 'background:#fff'>
                    <thead>
                    <th>Borrower Name</th>
                    <th colspan="{!! count($collectionSheet->loanProducts) !!}">Due Collections</th>
                    <th colspan="{!! count($collectionSheet->savingsProducts) !!}">Due Savings Collections</th>
                    <th>Attendance</th>
                    </thead>
                    <thead>
                    <th>Groups/Clients</th>
                    @foreach ($collectionSheet->loanProducts as $lp)
                        <th>{!! $lp->name !!}/Charges</th>
                    @endforeach
                    @foreach ($collectionSheet->savingsProducts as $sp)
                        <th>{!! $sp->name !!} (Saving Deposit)</th>
                    @endforeach
                    <th>Attendance</th>
                    </thead>
                    <tbody>

            @foreach($collectionSheet->groups as $group)
                            <tr>
                            <th colspan="{!! count($collectionSheet->loanProducts)+count($collectionSheet->savingsProducts)+2 !!}">{!! $group->groupName !!}</th>
                            </tr>
                        @foreach($group->clients as $client)
                            <tr>
                                <td>({!! $client->clientId !!}) {!! $client->clientName !!}</td>
                                @foreach ($collectionSheet->loanProducts as $lp)
                                    @if(isset($final_array[$group->groupId][$client->clientId]['loan'][$lp->id]->totalDue))
                                        <td><a href="#" class="edittable" id="{!! 'loan_'.$group->groupId.'_'.$client->clientId.'_'.$lp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['loan'][$lp->id]->totalDue !!}</a></td>
                                    @else
                                        <td><a href="#" class="edittable" id="{!! 'loan_'.$group->groupId.'_'.$client->clientId.'_'.$lp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                    @endif
                                @endforeach
                                @foreach ($collectionSheet->savingsProducts as $sp)
                                    @if(isset($final_array[$group->groupId][$client->clientId]['savings'][$sp->id]->dueAmount))
                                        <td><a href="#" class="edittable" id="{!! 'savings_'.$group->groupId.'_'.$client->clientId.'_'.$sp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['savings'][$sp->id]->dueAmount !!}</a></td>
                                    @else
                                        <td><a href="#" class="edittable" id="{!! 'savings_'.$group->groupId.'_'.$client->clientId.'_'.$sp->id !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
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
                            <th>Group Total</th>
                            @foreach ($collectionSheet->loanProducts as $lp)
                                @if(isset($sum[$group->groupId]['loan'][$lp->id]))
                                    <th>{!! number_format($sum[$group->groupId]['loan'][$lp->id],2) !!}</th>
                                @else
                                    <th>0</th>
                                @endif
                            @endforeach
                            @foreach ($collectionSheet->savingsProducts as $sp)
                                @if(isset($sum[$group->groupId]['savings'][$sp->id]))
                                    <th>{!! number_format($sum[$group->groupId]['savings'][$sp->id],2) !!}</th>
                                @else
                                    <th>0</th>
                                @endif
                            @endforeach

                            <td>
                            </td>
                        </tr>
            @endforeach
                    </tbody>
                </table>
                <div class="panel-heading">
                    <h3 class="panel-title">Totals</h3>
                </div>
                <div class="panel-body">

                <table id="searchData" class = "table table-bordered table-condensed" style = 'background:#fff'>
                    <thead>
                    <th>Groups/Clients</th>
                    <th>MML/Charges</th>
                    <th>CFL/Charges</th>
                    <th>MIL/Charges</th>
                    <th>TAC (Saving Deposit)</th>
                    <th>CCF (Saving Deposit)</th>
                    <th>Attendance</th>
                    </thead>
                    <tbody>
                <tr>
                    <td>Total Total</td>
                    @foreach ($collectionSheet->loanProducts as $lp)
                        @if(isset($totals_sum['loan'][$lp->id]))
                            <td>{!! number_format($totals_sum['loan'][$lp->id],2) !!}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    @foreach ($collectionSheet->savingsProducts as $sp)
                        @if(isset($totals_sum['savings'][$sp->id]))
                            <td>{!! $totals_sum['savings'][$sp->id] !!}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <td>
                    </td>
                </tr>
                </table>
                </div>

                <div class="panel-body">
                   <h3>Total Due Collections: </h3> <h3 class="panel-title total_due" id="total_due">{!! number_format($total_due,2) !!}</h3>
                </div>
                <div class="panel-body">
                    <h3 class="panel-title">Total Payment made: {!! number_format($payment->amount,2) !!}</h3>
                </div>
            </div>
        </div>
    </div>
	<!-- Modal -->
	{{--@include('partials.modal')--}}
	<!-- End of Modal -->
@stop
@push('scripts')
	<style>
		.numericCol{
			text-align: right;
		}
	</style>

    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script>
        $('.edittable').editable({
            type: 'text',
            mode:'inline',
            url: '/collectionSheetPost',
            title: 'Enter new value',
        });
        $('.editable').on('hidden', function(e, reason){
            if(reason === 'save' || reason === 'nochange') {
                var that = this;
                calculateTotal();
                $(".editable:visible").eq($(".editable:visible").index(that) + 1).editable('show');
            }
        });
        function calculateTotal(){
            var sum = 0;

            $(".edittable").each(function() {
                var val = $.trim( $(this).text() );

                if ( val ) {
                    val = parseFloat( val.replace( /^\$/, "" ) );

                    sum += !isNaN( val ) ? val : 0;
                }
            });
            document.getElementById('total_due').innerHTML=sum;
        }
    </script>
@endpush