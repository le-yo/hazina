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
                                @if(isset($client->loans[1]->totalDue))
                                <td><a href="#" class="edittable" id="{!! 'MML_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $client->loans[1]->totalDue !!}</a></td>
                                @else
                                    <td><a href="#" class="edittable" id="{!! 'group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($client->loans[2]->totalDue))
                                    <td><a href="#" class="edittable" id="{!! 'CFL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $client->loans[2]->totalDue !!}</a></td>

                                @else
                                    <td><a href="#" class="edittable" id="{!! 'CFL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($client->loans[0]->totalDue))
                                    <td><a href="#" class="edittable" id="{!! 'MIL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $client->loans[0]->totalDue !!}</a></td>

                                @else
                                    <td><a href="#" class="edittable" id="{!! 'MIL_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>

                                @endif
                                @if(isset($client->savings[0]->dueAmount))
                                    <td><a href="#" class="edittable" id="{!! 'TAC_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $client->savings[0]->dueAmount !!}</a></td>
                                @else
                                    <td><a href="#" class="edittable" id="{!! 'TAC_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">0</a></td>
                                @endif
                                @if(isset($client->savings[1]->dueAmount))
                                    <td><a href="#" class="edittable" id="{!! 'CCF_group_'.$group->groupId.'_client_'.$client->clientId !!}" data-type="text" data-pk="1" data-url="/collectionSheetPost" data-title="Total Due">{!! $client->savings[1]->dueAmount !!}</a></td>
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
                        </tbody>
                    </table>
				</div>
            @endforeach
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
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editable.defaults.highlight = '#bc64ff';
        $('.edittable').editable({
            type: 'text',
            url: '/collectionSheetPost',
            title: 'Enter new value',
        });
    </script>
@endpush