@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Content</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("teacher.course-contents.update", $courseContent->id) }}" enctype="multipart/form-data">
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
                                            <option value="{{ $data->id }}"  {{ (old('course_id') == $data->id || $courseContent->course_id == $data->id) ? 'selected' : '' }}>
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
                                    <input class="form-control {{ $errors->has('content_no') ? 'is-invalid' : '' }}" type="number" name="content_no" id="content_no" value="{{ old('content_no', $courseContent->content_no ?? '') }}" required>
                                    @if($errors->has('content_no'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('content_no') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="required" for="content_title">Title</label>
                                    <input class="form-control {{ $errors->has('content_title') ? 'is-invalid' : '' }}" type="text" name="content_title" id="content_title" value="{{ old('content_title', $courseContent->title ?? '') }}" required>
                                    @if($errors->has('content_title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('content_title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="required" for="content_description">Description</label>
                                    <textarea class="form-control {{ $errors->has('content_description') ? 'is-invalid' : '' }}" name="content_description" id="content_description">{{ old('content_description', $courseContent->description ?? '') }}</textarea>

                                    @if($errors->has('content_description'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('content_description') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="" for="class_time">Class Time</label>
                                    <input type="text" class="form-control datetimepicker {{ $errors->has('class_time') ? 'is-invalid' : '' }}" name="class_time" id="class_time" value="{{ old('class_time', $courseContent->class_time ?? '') }}">
                                    @if($errors->has('class_time'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('class_time') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="" for="class_link">Class Link</label>
                                    <input type="text" class="form-control {{ $errors->has('class_link') ? 'is-invalid' : '' }}" name="class_link" id="class_link" value="{{ old('class_link', $courseContent->class_link ?? '') }}">
                                    @if($errors->has('class_link'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('class_link') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12" id="course-status-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Content Status</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="required" for="content_status">Status</label>
                                    <select class="form-control {{ $errors->has('content_status') ? 'is-invalid' : '' }}" name="content_status" id="content_status">
                                        <option value="">Select</option>
                                        @foreach ($contentStatus as $key => $label)
                                            @php
                                                // Check if `old('status')` is null, and if so, set it to a -1
                                                $oldStatus = old('content_status') !== null ? old('content_status') : -1;
                                                $isSelected = $oldStatus == $key || (isset($courseContent) && $courseContent->status == $key);
                                            @endphp
                                            <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('content_status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('content_status') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <button class="btn btn-primary" type="submit" id="update-button">
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
    {{-- @include('layouts.common.ckeditor5.super_build_41_2_1') --}}
@endsection
