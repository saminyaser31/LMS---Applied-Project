@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Students {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Student- {{ $totalStudents }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('teacher.my-students.index') }}" method="GET">
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

                                            <div class="col-md-4 mt-3">
                                                <button type="submit" class="btn btn-success btn-md mr-3" style="padding: 6px 19px;"><i class="mdi mdi-file-search-outline"></i> Search</button>
                                                <a href="{{ route('teacher.my-students.index') }}" class="btn btn-warning btn-md">Clear</a>
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
                            <h4>Students List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($students->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Course</th>
                                            <th>Instructor</th>
                                            <th>Student Name</th>
                                            <th>Student Email</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($students as $key => $data)
                                        <tr>
                                            {{-- <td>{{ $students->firstItem() + $key }}</td> --}}
                                            <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                            <td>{{ $data->courses->title }}</td>
                                            <td>{{ $data->teacher->first_name . " " . $data->teacher->last_name }}</td>
                                            <td>{{ ($data->student) ? ($data->student->first_name . " " . $data->student->last_name) : null }}</td>
                                            <td>{{ ($data->student) ? ($data->student->email) : null }}</td>
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
                        {{ $students->withQueryString()->links('pagination::bootstrap-5') }}
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
