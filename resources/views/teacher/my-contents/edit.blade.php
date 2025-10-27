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
                <form method="POST" action="{{ route("teacher.my-contents.update", $teacherContent->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="">
                        <div class="card">
                            <div class="card-body">
                                @if(Auth::user()->user_type == App\Models\User::ADMIN)
                                    <div class="mb-3">
                                        <label class="required" for="teacher_id">Teacher</label>
                                        <select class="form-control js-example-basic-single {{ $errors->has('teacher_id') ? 'is-invalid' : '' }}" name="teacher_id" id="teacher_id">
                                            <option value="">Select</option>
                                            @if (isset($teachers))
                                                @foreach ($teachers as $key => $data)
                                                    <option value="{{ $data->user_id }}" {{ (old('teacher_id') == $data->user_id || $teacherContent->teacher_id == $data->user_id) ? 'selected' : '' }}>{{ $data->first_name . ' ' . $data->last_name }}</option>
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
                                            <option value="{{ $key }}" {{ old('content_type', $teacherContent->content_type) == $key ? 'selected' : '' }}>
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
                                            <option value="{{ $key }}" {{ old('upload_type', $teacherContent->file_type) == $key ? 'selected' : '' }}>
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
                                    @if ($teacherContent->file_type == App\Models\TeacherContents::TYPE_FILE)
                                        <a href="{{ $teacherContent->file_Path }}" target="_blank">View Existing File</a>
                                    @endif
                                    <input type="file" class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" name="file" id="file" value="{{ old('file') }}">
                                    @if($errors->has('file'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('file') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3" id="url_div">
                                    <label class="required" for="url">Url</label>
                                    <input type="text" class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" name="url" id="url" value="{{ old('url', $teacherContent->file_path) }}">
                                    @if($errors->has('url'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('url') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3" id="thumbnail_div">
                                    <label class="required" for="thumbnail">Thumbnail (650 * 650)</label>
                                    <a href="{{ $teacherContent->thumbnail }}" target="_blank">View Existing Thumbnail</a>

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

                    <div class="col-lg-12" id="content-status-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Status</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="required" for="content_status">Content status</label>
                                    <select class="form-control js-example-basic-single {{ $errors->has('content_status') ? 'is-invalid' : '' }}" name="content_status" id="content_status">
                                        <option value="">Select</option>
                                        @foreach (App\Models\TeacherContents::STATUS_SELECT as $key => $label)
                                            @php
                                                // Check if `old('status')` is null, and if so, set it to a -1
                                                $oldStatus = old('content_status') !== null ? old('content_status') : -1;
                                                $isSelected = $oldStatus == $key || (isset($teacherContent) && $teacherContent->status == $key);
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
