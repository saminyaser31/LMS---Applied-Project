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
                    <h4 class="mb-sm-0">{{ trans('global.create') }} Course Material</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <form method="POST" action="{{ route("teacher.course-materials.store") }}" enctype="multipart/form-data">
                @csrf

                <div class="col-lg-12" id="course-content-section">
                    <div class="card" id="content-card-template">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Material</h4>
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
                                <label class="" for="title">Title</label>
                                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title">
                                @if($errors->has('title'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('title') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="material_type">Material Type</label>
                                <select class="form-control js-example-basic-single {{ $errors->has('material_type') ? 'is-invalid' : '' }}" name="material_type" id="material_type" required>
                                    <option value="">Select</option>
                                    @foreach (App\Models\CourseMaterials::MATERIAL_TYPE as $key => $data)
                                        <option value="{{ $key }}">{{ $data }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('material_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('material_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="upload_type">Upload Type</label>
                                <select class="form-control js-example-basic-single {{ $errors->has('upload_type') ? 'is-invalid' : '' }}" name="upload_type" id="upload_type" required>
                                    <option value="">Select</option>
                                    @foreach (App\Models\CourseMaterials::UPLOAD_TYPE as $key => $data)
                                        <option value="{{ $key }}" {{ request('upload_type', '') == $key ? 'selected' : '' }}>
                                            {{ $data }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('upload_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('upload_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3" id="file_div">
                                <label class="required" for="file">File</label>
                                <input type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" name="file" id="file" value="{{ old('file') }}">
                                @if($errors->has('file'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('file') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3" id="url_div">
                                <label class="required" for="url">Url</label>
                                <input type="text" class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" name="url" id="url" value="{{ old('url') }}">
                                @if($errors->has('url'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('url') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="remarks">Remarks</label>
                                <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" name="remarks" id="remarks">{{ old('remarks') }}</textarea>
                                @if($errors->has('remarks'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('remarks') }}
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
    <script>
        $(document).ready(function() {
            var typeFile = {!! App\Models\CourseMaterials::TYPE_FILE !!};
            var typeUrl = {!! App\Models\CourseMaterials::TYPE_URL !!};
            function toggleFields() {
                var uploadType = $('#upload_type').val();
                if (uploadType == typeFile) {
                    $('#file_div').show();
                    $('#url_div').hide();
                } else if (uploadType == typeUrl) {
                    $('#file_div').hide();
                    $('#url_div').show();
                } else {
                    $('#file_div').hide();
                    $('#url_div').hide();
                }
            }

            toggleFields();

            $('#upload_type').change(function() {
                toggleFields();
            });
        });
    </script>
@endsection
