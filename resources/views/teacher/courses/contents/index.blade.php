@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Content {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('teacher.course-contents.create') }}">
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
                            Total Content- {{ $totalCourseContents }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('teacher.course-contents.index') }}" method="GET">
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
                                                    <label class="" for="course_category">Course Category</label>
                                                    <select class="form-control js-example-basic-single {{ $errors->has('course_category') ? 'is-invalid' : '' }}" name="course_category" id="course_category">
                                                        <option value="">Select</option>
                                                        @foreach ($courseCategory as $key => $value)
                                                            <option value="{{ $key }}" {{ request('course_category', '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="course_id">Course</label>
                                                    <select class="form-control js-example-basic-single {{ $errors->has('course_id') ? 'is-invalid' : '' }}" name="course_id" id="course_id">
                                                        <option value="">Select</option>
                                                        @foreach ($courses as $key => $data)
                                                            <option value="{{ $data->id }}" {{ request('course_id', '') == $data->id ? 'selected' : '' }}>
                                                                @if (Auth::user() && Auth::user()->user_type == App\Models\User::ADMIN)
                                                                    {{ $data->title }} - {{ $data->courseTeacher->first_name }}
                                                                @else
                                                                    {{ $data->title }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="content_status">Content status</label>
                                                    <select class="form-control js-example-basic-single {{ $errors->has('content_status') ? 'is-invalid' : '' }}" name="content_status" id="content_status">
                                                        <option value="">Select</option>
                                                        @foreach ($contentStatus as $key => $label)
                                                            <option {{ request('content_status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('teacher.course-contents.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                                @if (!($courseContents->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Course</th>
                                            <th>Content No</th>
                                            <th>Title</th>
                                            <th>Class Time</th>
                                            <th>Class Link</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($courseContents as $key => $content)
                                        <tr>
                                            {{-- <td>{{ $courseContents->firstItem() + $key }}</td> --}}
                                            <td>{{ ($courseContents->currentPage() - 1) * $courseContents->perPage() + $loop->iteration }}</td>
                                            <td>{{ $content->course ? $content->course->title : null }}</td>
                                            <td>{{ $content->content_no }}</td>
                                            <td>{{ $content->title }}</td>
                                            <td>{{ ($content->class_time) }}</td>
                                            <td>{{ ($content->class_link) }}</td>
                                            <td>{{ App\Models\CourseContents::STATUS_SELECT[$content->status] }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('teacher.course-contents.edit', $content->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                    href="{{ route('teacher.course-contents.delete', $content->id) }}">
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
                        {{ $courseContents->withQueryString()->links('pagination::bootstrap-5') }}
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
