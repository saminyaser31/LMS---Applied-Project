@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Course {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('teacher.courses.create') }}">
                                {{ trans('global.add') }} Course
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Course- {{ $totalCourses }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('teacher.courses.index') }}" method="GET">
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
                                                    <select class="form-control search select2 {{ $errors->has('course_category') ? 'is-invalid' : '' }}" name="course_category" id="course_category">
                                                        <option value="">Select</option>
                                                        @foreach ($courseCategory as $key => $value)
                                                            <option value="{{ $key }}" {{ request('course_category', '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="subject_id">Subject</label>
                                                    <select class="form-control search select2 {{ $errors->has('subject_id') ? 'is-invalid' : '' }}" name="subject_id" id="subject_id">
                                                        <option value="">Select</option>
                                                        @foreach ($courseSubject as $key => $value)
                                                            <option value="{{ $key }}" {{ request('subject_id', '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="level_id">Level</label>
                                                    <select class="form-control search select2 {{ $errors->has('level_id') ? 'is-invalid' : '' }}" name="level_id" id="level_id">
                                                        <option value="">Select</option>
                                                        @foreach ($courseLevel as $key => $value)
                                                            <option value="{{ $key }}" {{ request('level_id', '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="course_title">Title</label>
                                                    <input class="form-control" value="{{ request('course_title') }}" type="text" name="course_title" id="course_title">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="" for="course_status">Course status</label>
                                                    <select class="form-control {{ $errors->has('course_status') ? 'is-invalid' : '' }}" name="course_status" id="course_status">
                                                        <option value="">Select</option>
                                                        @foreach ($courseStatus as $key => $label)
                                                            <option {{ request('course_status', '') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('teacher.courses.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                            <h4>Course List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($courses->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Subject</th>
                                            <th>Level</th>
                                            <th>Instructor</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            {{-- <th>Created By</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($courses as $key => $course)
                                        <tr>
                                            {{-- <td>{{ $courses->firstItem() + $key }}</td> --}}
                                            <td>{{ ($courses->currentPage() - 1) * $courses->perPage() + $loop->iteration }}</td>
                                            <td>{{ $course->title }}</td>
                                            <td>{{ ($course->courseCategory) ? $course->courseCategory->name : '' }}</td>
                                            <td>{{ ($course->courseSubject) ? $course->courseSubject->name : '' }}</td>
                                            <td>{{ ($course->courseLevel) ? $course->courseLevel->name : '' }}</td>
                                            <td>{{ ($course->courseTeacher) ? $course->courseTeacher->email : '' }}</td>
                                            <td>{{ $course->price }}</td>
                                            <td>
                                                @if ($course->status == App\Models\Course::STATUS_ENABLE)
                                                    <span class="badge bg-success">{{ App\Models\Course::STATUS_SELECT[$course->status] }}</span>
                                                @elseif ($course->status == App\Models\Course::STATUS_DISABLE)
                                                    <span class="badge bg-danger">{{ App\Models\Course::STATUS_SELECT[$course->status] }}</span>
                                                @elseif ($course->status == App\Models\Course::STATUS_PENDING)
                                                    <span class="badge bg-warning">{{ App\Models\Course::STATUS_SELECT[$course->status] }}</span>
                                                @endif
                                            </td>
                                            {{-- <td>{{ ($course->createdBy) ? $course->createdBy->email : '' }}</td> --}}
                                            <td>
                                                <a class="btn btn-sm btn-primary mt-2"
                                                    href="{{ route('teacher.courses.show', $course->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>

                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('teacher.courses.edit', $course->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                    href="{{ route('teacher.courses.delete', $course->id) }}">
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
                        {{ $courses->withQueryString()->links('pagination::bootstrap-5') }}
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
