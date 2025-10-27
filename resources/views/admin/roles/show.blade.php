@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
                        </div>
                        <div class="card-body">
                            <div class="form-group my-3">
                                <a class="btn btn-soft-dark" href="{{ route('admin.roles.index') }}">
                                    {{ trans('global.back_to_list') }}
                                </a>
                            </div>
                            <table id="example" class="table table-bordered dt-responsive table-striped align-middle" style="width:100%">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.role.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $role->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.role.fields.title') }}
                                        </th>
                                        <td>
                                            {{ $role->title }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.role.fields.permissions') }}
                                        </th>
                                        <td>
                                            @foreach ($role->permissions as $key => $permissions)
                                                <span class="badge bg-secondary">{{ $permissions->title }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group my-3">
                                <a class="btn btn-soft-dark" href="{{ route('admin.roles.index') }}">
                                    {{ trans('global.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
