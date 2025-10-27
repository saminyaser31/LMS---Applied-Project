@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
                        </div>
                        <div class="card-body">
                            <div class="form-group my-3">
                                <a class="btn btn-soft-dark" href="{{ route('admin.audit-logs.index') }}">
                                    {{ trans('global.back_to_list') }}
                                </a>
                            </div>
                            <table id="example" class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                <tbody>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.id') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.description') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->description }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.subject_id') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->subject_id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.subject_type') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->subject_type }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.user_id') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->user_id }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.properties') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->properties }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.host') }}
                                        </th>
                                        <td>
                                            {{ $auditLog->host }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.auditLog.fields.created_at') }}
                                        </th>
                                        <td>
                                            {{ frontEndTimeConverterView($auditLog->created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group my-3">
                                <a class="btn btn-soft-dark" href="{{ route('admin.audit-logs.index') }}">
                                    {{ trans('global.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
