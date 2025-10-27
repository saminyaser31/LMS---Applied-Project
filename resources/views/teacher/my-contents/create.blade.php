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
                    <h4 class="mb-sm-0">{{ trans('global.create') }} Content</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <form method="POST" action="{{ route("teacher.my-contents.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-12" id="">
                    <div class="card">
                        <div class="card-body">
                            @if(Auth::user()->user_type == App\Models\User::ADMIN)
                                <div class="mb-3">
                                    <label class="required" for="teacher_id">Teacher</label>
                                    <select class="form-control search select2 {{ $errors->has('teacher_id') ? 'is-invalid' : '' }}" name="teacher_id" id="teacher_id">
                                        <option value="">Select</option>
                                        @if (isset($teachers))
                                            @foreach ($teachers as $key => $data)
                                                <option value="{{ $data->user_id }}" {{ old('teacher_id') == $data->user_id ? 'selected' : '' }}>{{ $data->first_name . ' ' . $data->last_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('teacher_id'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('teacher_id') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="required" for="content_type">Content Type</label>
                                <select class="form-control js-example-basic-single {{ $errors->has('content_type') ? 'is-invalid' : '' }}" name="content_type" id="content_type" required>
                                    <option value="">Select</option>
                                    @foreach (App\Models\TeacherContents::CONTENT_TYPE_SELECT as $key => $data)
                                        <option value="{{ $key }}" {{ request('content_type', '') == $key ? 'selected' : '' }}>
                                            {{ $data }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($errors->has('content_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="upload_type">Upload Type</label>
                                <select class="form-control js-example-basic-single {{ $errors->has('upload_type') ? 'is-invalid' : '' }}" name="upload_type" id="upload_type" required>
                                    <option value="">Select</option>
                                    @foreach (App\Models\TeacherContents::FILE_TYPE_SELECT as $key => $data)
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

                            <div class="mb-3" id="thumbnail_div">
                                <label class="required" for="thumbnail">Thumbnail (650 * 650)</label>
                                <input type="file" class="form-control {{ $errors->has('thumbnail') ? 'is-invalid' : '' }}" name="thumbnail" id="thumbnail" required>
                                @if($errors->has('thumbnail'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('thumbnail') }}
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

    <script>
        $(document).ready(function() {
            var typeFile = {!! App\Models\TeacherContents::TYPE_FILE !!};
            var typeUrl = {!! App\Models\TeacherContents::TYPE_URL !!};
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
