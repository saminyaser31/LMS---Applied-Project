@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Teacher {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('admin.teachers.create') }}">
                                {{ trans('global.add') }} Teacher
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Teacher- {{ $totalTeachers }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.teachers.index') }}" method="GET">
                                        <div class="row d-flex flex-row align-items-center card-body">

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="email">Email</label>
                                                    <input class="form-control" value="{{ request('email') }}" type="text" name="email" id="email">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="phone_no">Phone No</label>
                                                    <input class="form-control" value="{{ request('phone_no') }}" type="text" name="phone_no" id="phone_no">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="" for="approved">Approved</label>
                                                    <select class="form-control {{ $errors->has('approved') ? 'is-invalid' : '' }}" name="approved" id="approved">
                                                        <option value="">Select</option>
                                                        @foreach ($userStatus as $key => $label)
                                                            <option {{ request('approved', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="" for="teacher_status">Status</label>
                                                    <select class="form-control {{ $errors->has('teacher_status') ? 'is-invalid' : '' }}" name="teacher_status" id="teacher_status">
                                                        <option value="">Select</option>
                                                        @foreach ($teacherStatus as $key => $label)
                                                            <option {{ request('teacher_status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('admin.teachers.index') }}" class="btn btn-warning btn-md">Clear</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Teacher List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($teachers->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No</th>
                                            <th>Approved</th>
                                            <th>Status</th>
                                            <th>Created By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($teachers as $key => $teacher)
                                        <tr>
                                            {{-- <td>{{ $teachers->firstItem() + $key }}</td> --}}
                                            <td>{{ ($teachers->currentPage() - 1) * $teachers->perPage() + $loop->iteration }}</td>
                                            <td>{{ $teacher->first_name . ' ' . $teacher->last_name }}</td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>{{ $teacher->phone_no }}</td>
                                            <td>{{ ($teacher->user) ? App\Models\User::STATUS_SELECT[$teacher->user->approved] : '' }}</td>
                                            <td>{{ App\Models\Teacher\Teachers::STATUS_SELECT[$teacher->status] }}</td>
                                            <td>{{ ($teacher->createdBy) ? $teacher->createdBy->email : '' }}</td>
                                            <td>
                                                @can('access_change_password')
                                                    <a class="btn btn-sm btn-warning mt-2"
                                                        href="{{ route('admin.teachers.change-password', $teacher->user_id) }}">
                                                        Change Password
                                                    </a>
                                                @endcan

                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('admin.teachers.edit', $teacher->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                    href="{{ route('admin.teachers.delete', $teacher->id) }}">
                                                    {{ trans('global.delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td rowspan="5">
                                            <h5 class="text-center">Not available</h5>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        {{ $teachers->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        @parent
        <script>
        </script>
    @endsection
