@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Contents {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('teacher.my-contents.create') }}">
                                {{ trans('global.add') }} Content
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Content- {{ $totalTeacherContents }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('teacher.my-contents.index') }}" method="GET">
                                        <div class="row d-flex flex-row align-items-center card-body">

                                            @if(Auth::user()->user_type == App\Models\User::ADMIN)
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="required" for="teacher_id">Teacher</label>
                                                        <select class="form-control search select2 {{ $errors->has('teacher_id') ? 'is-invalid' : '' }}" name="teacher_id" id="teacher_id">
                                                            <option value="">Select</option>
                                                            @if (isset($teachers))
                                                                @foreach ($teachers as $key => $data)
                                                                    <option value="{{ $data->user_id }}" {{ request('teacher_id', '') == $data->user_id ? 'selected' : '' }}>{{ $data->first_name . ' ' . $data->last_name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @if($errors->has('teacher_id'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('teacher_id') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="content_type">Content Type</label>
                                                    <select class="form-control {{ $errors->has('content_type') ? 'is-invalid' : '' }}" name="content_type" id="content_type">
                                                        <option value="">Select</option>
                                                        @foreach (App\Models\TeacherContents::CONTENT_TYPE_SELECT as $key => $label)
                                                            <option {{ request('content_type', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="file_type">File Type</label>
                                                    <select class="form-control {{ $errors->has('file_type') ? 'is-invalid' : '' }}" name="file_type" id="file_type">
                                                        <option value="">Select</option>
                                                        @foreach (App\Models\TeacherContents::FILE_TYPE_SELECT as $key => $label)
                                                            <option {{ request('file_type', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('teacher.my-contents.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                            <h4>Content List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($teacherContents->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Content Type</th>
                                            <th>File Type</th>
                                            <th>File</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($teacherContents as $key => $data)
                                        <tr>
                                            <td>{{ ($teacherContents->currentPage() - 1) * $teacherContents->perPage() + $loop->iteration }}</td>
                                            <td>{{ App\Models\TeacherContents::CONTENT_TYPE_SELECT[$data->content_type] }}</td>
                                            <td>{{ App\Models\TeacherContents::FILE_TYPE_SELECT[$data->file_type] }}</td>
                                            <td>{{ $data->file_path }}</td>
                                            <td>{{ App\Models\TeacherContents::STATUS_SELECT[$data->status] }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('teacher.my-contents.edit', $data->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                    href="{{ route('teacher.my-contents.delete', $data->id) }}">
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
                        {{ $teacherContents->withQueryString()->links('pagination::bootstrap-5') }}
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
