@extends('layouts.common.master')
@section('css')
    @parent
    <style>
        /* #course-content-section .ck-editor__editable {
            min-height: 100px !important;
        } */
    </style>
@endsection

@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ trans('global.create') }} Course Content</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <form method="POST" action="{{ route("teacher.course-contents.store") }}" enctype="multipart/form-data">
                @csrf

                <div class="col-lg-12" id="course-content-section">
                    <div class="card" id="content-card-template">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Content</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="course_id">Course</label>
                                <select class="form-control js-example-basic-single {{ $errors->has('course_id') ? 'is-invalid' : '' }}" name="course_id" id="course_id" required>
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
                                @if($errors->has('course_id'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_id') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="content_no">No</label>
                                <input class="form-control {{ $errors->has('content_no') ? 'is-invalid' : '' }}" type="number" name="content_no" id="content_no" required>
                                @if($errors->has('content_no'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_no') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="content_title">Title</label>
                                <input class="form-control {{ $errors->has('content_title') ? 'is-invalid' : '' }}" type="text" name="content_title" id="content_title" required>
                                @if($errors->has('content_title'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_title') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="content_description">Description</label>
                                <textarea class="form-control {{ $errors->has('content_description') ? 'is-invalid' : '' }}" name="content_description" id="content_description"></textarea>

                                @if($errors->has('content_description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_description') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="class_time">Class Time</label>
                                <input type="text" class="form-control datetimepicker {{ $errors->has('class_time') ? 'is-invalid' : '' }}" name="class_time" id="class_time" value="{{ old('class_time') }}">
                                @if($errors->has('class_time'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('class_time') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="class_link">Class Link</label>
                                <input type="text" class="form-control {{ $errors->has('class_link') ? 'is-invalid' : '' }}" name="class_link" id="class_link" value="{{ old('class_link') }}">
                                @if($errors->has('class_link'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('class_link') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 text-start">
                    <button class="btn btn-primary" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    @parent

    @include('layouts.common.ckeditor5.43_3_0')
@endsection
