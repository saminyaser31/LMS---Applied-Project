@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">New Teacher Requests {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Requests- {{ $totalRequests }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.teachers.approve-requests.index') }}" method="GET">
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

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('admin.teachers.approve-requests.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                            <h4>Requests List</h4>
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
                                            <td>
                                                <span class="badge bg-warning">{{ App\Models\User::STATUS_SELECT[$teacher->user->approved] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ App\Models\Teacher\Teachers::STATUS_SELECT[$teacher->status] }}</span>
                                            </td>
                                            <td>{{ ($teacher->createdBy) ? $teacher->createdBy->email : '' }}</td>
                                            <td>
                                                @can('access_change_password')
                                                    <a class="btn btn-sm btn-warning mt-2" target="_blank"
                                                        href="{{ route('admin.teachers.show', $teacher->id) }}">
                                                        {{ trans('global.show') }}
                                                    </a>
                                                @endcan

                                                @can('approve_new_teacher_requests')
                                                    <a class="btn btn-sm btn-info mt-2"
                                                        href="{{ route('admin.teachers.approve-requests.update', $teacher->user_id) }}" onclick="return confirm('{{ trans('global.areYouSure') }}');">
                                                        Approve
                                                    </a>
                                                @endcan
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
