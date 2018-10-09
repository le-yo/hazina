@extends('centaur.layout')

@section('title', 'Payments')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-info">
            @foreach($collectionSheet->groups as $group)

				<div class="panel-heading">
					<h3 class="panel-title">{!! $group->groupName !!}</h3>
				</div>
				<div class="panel-body">

                    {{--<a href='{!!url("member")!!}/create' class = 'btn btn-success'><i class="fa fa-plus"></i> New</a>--}}
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
                        @foreach($group->clients as $client)
                            <tr>
                                <td>{!! $client->clientName !!}</td>

                                {{--<td><a href="#" id="username" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Enter username">superuser</a></td>--}}
                                @if(isset($final_array[$group->groupId][$client->clientId]['loan'][2]->totalDue))
                                <td><a href="#" class="edittable" id="{!! 'MML_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['loan'][2]->totalDue !!}</a></td>
                                @else
                                    <td><a href="#" class="edittable" id="{!! 'group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($final_array[$group->groupId][$client->clientId]['loan'][5]->totalDue))
                                    <td><a href="#" class="edittable" id="{!! 'CFL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['loan'][5]->totalDue !!}</a></td>

                                @else
                                    <td><a href="#" class="edittable" id="{!! 'CFL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($final_array[$group->groupId][$client->clientId]['loan'][6]->totalDue))
                                    <td><a href="#" class="edittable" id="{!! 'MIL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['loan'][6]->totalDue !!}</a></td>

                                @else
                                    <td><a href="#" class="edittable" id="{!! 'MIL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>

                                @endif
                                @if(isset($final_array[$group->groupId][$client->clientId]['savings'][7]->dueAmount))
                                    <td><a href="#" class="edittable" id="{!! 'TAC_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['savings'][7]->dueAmount !!}</a></td>
                                @else
                                    <td><a href="#" class="edittable" id="{!! 'TAC_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($final_array[$group->groupId][$client->clientId]['savings'][3]->dueAmount))
                                    <td><a href="#" class="edittable" id="{!! 'CCF_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $final_array[$group->groupId][$client->clientId]['savings'][3]->dueAmount !!}</a></td>
                                @else
                                    <td><a href="#" class="edittable" id="{!! 'CCF_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
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
                            <td>Group Total</td>
                            {{--<td><a href="#" id="username" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Enter username">superuser</a></td>--}}
                            @if(isset($sum[$group->groupId]['loan'][2]))
                                <td>{!! $sum[$group->groupId]['loan'][2] !!}</td>
                            @else
                                <td>0</td>
                            @endif
                            @if(isset($sum[$group->groupId]['loan'][5]))
                                <td>{!! $sum[$group->groupId]['loan'][5] !!}</td>
                            @else
                                <td>0</td>
                            @endif
                            @if(isset($sum[$group->groupId]['loan'][6]))
                                <td>{!! $sum[$group->groupId]['loan'][6] !!}</td>
                            @else
                                <td>0</td>
                            @endif

                            @if(isset($sum[$group->groupId]['savings'][7]))
                                <td>{!! $sum[$group->groupId]['savings'][7] !!}</td>
                            @else
                                <td>0</td>
                            @endif
                            @if(isset($sum[$group->groupId]['savings'][3]))
                                <td>{!! $sum[$group->groupId]['savings'][3] !!}</td>
                            @else
                                <td>0</td>
                            @endif

                            <td>
                            </td>
                        </tr>
                        </tbody>
                    </table>
				</div>
            @endforeach
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
                    @if(isset($totals_sum['loan'][2]))
                        <td>{!! $totals_sum['loan'][2] !!}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if(isset($totals_sum['loan'][5]))
                        <td>{!! $totals_sum['loan'][5] !!}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if(isset($totals_sum['loan'][6]))
                        <td>{!! $totals_sum['loan'][6] !!}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if(isset($totals_sum['savings'][7]))
                        <td>{!! $totals_sum['savings'][7] !!}</td>
                    @else
                        <td>0</td>
                    @endif
                    @if(isset($totals_sum['savings'][3]))
                        <td>{!! $totals_sum['savings'][3] !!}</td>
                    @else
                        <td>0</td>
                    @endif
                    <td>
                    </td>
                </tr>
                    </tbody>
                </table>
                </div>

                <div class="panel-heading">
                    <h3 class="panel-title">Total Due Collections: {!! $totals_sum['loan'][2] +  $totals_sum['loan'][5] +  $totals_sum['loan'][6] + $totals_sum['savings'][7] + $totals_sum['savings'][3]!!}</h3>
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
            url: '/collectionSheetPost',
            title: 'Enter new value',
        });
    </script>
@endpush