@extends('Centaur::layout')

@section('title', 'Roles')

@section('content')
    <div class="page-header">
        <div class='btn-toolbar pull-right'>
            <a class="btn btn-info btn-lg" href="{{ route('roles.create') }}">
                <span class="icon-plus" aria-hidden="true"></span>
                Create Role
            </a>
        </div>
        <h1>Roles</h1>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Permissions</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->slug }}</td>
                                <td>{{ implode(", ", array_keys($role->permissions)) }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-default">
                                        <span class="icon-pencil" aria-hidden="true"></span>
                                        Edit
                                    </a>
                                    <a href="{{ route('roles.destroy', $role->id) }}" class="btn btn-danger formConfirm {{ ($role->slug == 'administrator') ? 'disabled' : '' }}"
                                        data-method="delete"
                                        data-token="{{ csrf_token() }}"
                                        data-toggle="modal"
                                        data-target="#modal-confirm"
                                        data-message="Are you sure you want to delete {{ $role->name }}?"
                                        data-title="Please Confirm"
                                        data-id="frmDelete-{{ $role->id }}"
                                        data-form="#frmDelete-{{ $role->id }}">
                                        {!! ($role->slug == 'administrator') ? '<span class="icon-ban" aria-hidden="true"></span> Delete' : '<span class="icon-trash" aria-hidden="true"></span> Delete' !!}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    @include('partials.modal')
    <!-- End of Delete Confirmation Modal -->
@stop