@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.show') }} Course</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12" id="course-basic-section">
                    <div class="card">
                        <div class="card-body">
                            @if(Auth::user()->user_type == App\Models\User::ADMIN)
                                <div class="mb-3">
                                    <label class="required" for="teacher_id">Teacher</label>
                                    <select class="form-control search select2 {{ $errors->has('teacher_id') ? 'is-invalid' : '' }}" name="teacher_id" id="teacher_id" disabled>
                                        <option value="">Select</option>
                                        @if (isset($teachers))
                                            @foreach ($teachers as $key => $data)
                                                <option value="{{ $data->user_id }}" {{ (old('teacher_id') == $data->user_id || $course->teacher_id == $data->user_id) ? 'selected' : '' }}>{{ $data->first_name . ' ' . $data->last_name }}</option>
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
                                <label class="required" for="course_category">Course Category</label>
                                <select class="form-control search select2 {{ $errors->has('course_category') ? 'is-invalid' : '' }}" name="course_category" id="course_category" disabled>
                                    <option value="">Select</option>
                                    @foreach ($courseCategory as $key => $value)
                                        <option value="{{ $key }}" {{ (old('course_category') == $key || $course->category_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('course_category'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_category') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="subject_id">Subject</label>
                                <select class="form-control search select2 {{ $errors->has('subject_id') ? 'is-invalid' : '' }}" name="subject_id" id="subject_id" disabled>
                                    <option value="">Select</option>
                                    @foreach ($courseSubject as $key => $value)
                                        <option value="{{ $key }}" {{ (old('subject_id') == $key || $course->subject_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="required" for="level_id">Level</label>
                                <select class="form-control search select2 {{ $errors->has('level_id') ? 'is-invalid' : '' }}" name="level_id" id="level_id" disabled>
                                    <option value="">Select</option>
                                    @foreach ($courseLevel as $key => $value)
                                        <option value="{{ $key }}" {{ (old('level_id') == $key || $course->level_id == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="required" for="title">Title</label>
                                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $course->title ?? '') }}" disabled>
                                @if($errors->has('title'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('title') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="short_description">Short Description</label>
                                <textarea class="form-control {{ $errors->has('short_description') ? 'is-invalid' : '' }} ckeditor-classic" name="short_description" id="short_description" disabled>{{ old('short_description', $course->short_description ?? '') }}</textarea>

                                @if($errors->has('short_description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('short_description') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="long_description">Long Description (What you'll learn)</label>
                                <textarea class="form-control {{ $errors->has('long_description') ? 'is-invalid' : '' }} ckeditor-classic" name="long_description" id="long_description" disabled>{{ old('long_description', $course->long_description ?? '') }}</textarea>

                                @if($errors->has('long_description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('long_description') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="course_start_date">Course Starts Date</label>
                                <input type="text" class="form-control datetimepicker {{ $errors->has('course_start_date') ? 'is-invalid' : '' }}" name="course_start_date" id="course_start_date" value="{{ old('course_start_date', $course->course_start_date ?? '') }}" disabled>
                                @if($errors->has('course_start_date'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_start_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="course-content-section">
                    <div class="card" id="content-card-template">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Content</h4>
                            <div class="flex-shrink-0">
                                <div class="form-check form-switch form-switch-right form-switch-md">
                                    <button class="btn btn-danger btn-sm delete-btn" style="display: none;">Delete</button>
                                    <a href="javascript:void(0)" class="btn btn-success btn-sm add-btn">Add Content</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="content_title">Title</label>
                                <input class="form-control {{ $errors->has('content_title') ? 'is-invalid' : '' }}" type="text" name="content_title[]" id="content_title" disabled>
                                @if($errors->has('content_title'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_title') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="required" for="content_description">Description</label>
                                <textarea class="form-control {{ $errors->has('content_description') ? 'is-invalid' : '' }}" name="content_description[]" id="content_description"></textarea>

                                @if($errors->has('content_description'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('content_description') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hidden inputs to store old values -->
                @php
                    $oldContentTitles = old('content_title', []);
                    $oldContentDescriptions = old('content_description', []);
                @endphp

                <div class="col-lg-12" id="course-requirment-section">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Requirement</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="requirments">Requirements</label>
                                <textarea class="form-control {{ $errors->has('requirments') ? 'is-invalid' : '' }} ckeditor-classic" name="requirments" id="requirments" disabled>{{ old('requirments', $course->requirments ?? '') }}</textarea>

                                @if($errors->has('requirments'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('requirments') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="course-include-section">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Includes</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="total_class">Total Class</label>
                                <input class="form-control mb-3 {{ $errors->has('total_class') ? 'is-invalid' : '' }}" type="number" name="total_class" id="total_class" value="{{ old('total_class', $course->total_class ?? '') }}" disabled>
                                @if($errors->has('total_class'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('total_class') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('certificate') ? 'is-invalid' : '' }}" type="checkbox" name="certificate" id="certificate" value="1" {{ old('certificate', $course->certificate ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="certificate">
                                        Certificate of completion
                                    </label>
                                    @if($errors->has('certificate'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('certificate') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('quizes') ? 'is-invalid' : '' }}" type="checkbox" name="quizes" id="quizes" value="1" {{ old('quizes', $course->quizes ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="quizes">
                                        Quizzes and Assessments
                                    </label>
                                    @if($errors->has('quizes'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('quizes') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('qa') ? 'is-invalid' : '' }}" type="checkbox" name="qa" id="qa" value="1" {{ old('qa', $course->qa ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="qa">
                                        Q&A Sessions
                                    </label>
                                    @if($errors->has('qa'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('qa') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('study_tips') ? 'is-invalid' : '' }}" type="checkbox" name="study_tips" id="study_tips" value="1" {{ old('study_tips', $course->study_tips ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="study_tips">
                                        Study Tips and Strategies
                                    </label>
                                    @if($errors->has('study_tips'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('study_tips') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('career_guidance') ? 'is-invalid' : '' }}" type="checkbox" name="career_guidance" id="career_guidance" value="1" {{ old('career_guidance', $course->career_guidance ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="career_guidance">
                                        Career Guidance
                                    </label>
                                    @if($errors->has('career_guidance'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('career_guidance') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('progress_tracking') ? 'is-invalid' : '' }}" type="checkbox" name="progress_tracking" id="progress_tracking" value="1" {{ old('progress_tracking', $course->progress_tracking ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="progress_tracking">
                                        Progress Tracking
                                    </label>
                                    @if($errors->has('progress_tracking'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('progress_tracking') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input {{ $errors->has('flex_learning_pace') ? 'is-invalid' : '' }}" type="checkbox" name="flex_learning_pace" id="flex_learning_pace" value="1" {{ old('flex_learning_pace', $course->flex_learning_pace ?? null) == 1 ? 'checked' : '' }} disabled>
                                    <label class="form-check-label" for="flex_learning_pace">
                                        Flexible Learning Pace
                                    </label>
                                    @if($errors->has('flex_learning_pace'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('flex_learning_pace') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="course-pricing-section">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Pricing</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="price">Price</label>
                                <input class="form-control mb-3 {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', $course->price ?? '') }}" disabled>
                                @if($errors->has('price'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('price') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="discount_type">Discount Type</label>
                                <select class="form-control select2 {{ $errors->has('discount_type') ? 'is-invalid' : '' }}" name="discount_type" id="discount_type" disabled>
                                    <option value="">Select</option>
                                    @foreach (App\Models\Course::TYPE_ARRAY as $key => $value)
                                        <option value="{{ $key }}" {{ (old('discount_type') == $key || $course->discount_type == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('discount_type'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount_type') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="discount_amount">Discount Amount</label>
                                <input class="form-control {{ $errors->has('discount_amount') ? 'is-invalid' : '' }}" type="text" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $course->discount_amount ?? '') }}" disabled>
                                @if($errors->has('discount_amount'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount_amount') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="discount_start_date">Discount Start Date</label>
                                <input type="text" class="form-control datetimepicker {{ $errors->has('discount_start_date') ? 'is-invalid' : '' }}" name="discount_start_date" id="discount_start_date" value="{{ old('discount_start_date', $course->discount_start_date ?? '') }}" disabled>
                                @if($errors->has('discount_start_date'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount_start_date') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="discount_expiry_date">Discount Expiry Date</label>
                                <input type="text" class="form-control datetimepicker {{ $errors->has('discount_expiry_date') ? 'is-invalid' : '' }}" name="discount_expiry_date" id="discount_expiry_date" value="{{ old('discount_expiry_date', $course->discount_expiry_date ?? '') }}" disabled>
                                @if($errors->has('discount_expiry_date'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('discount_expiry_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="course-media-section">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Promotional Media</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="" for="course_card_image">Course Card Image (710 * 488)</label>
                                {{-- <input class="form-control mb-3 {{ $errors->has('course_card_image') ? 'is-invalid' : '' }}" type="file" name="course_card_image" id="course_card_image" accept="image/*"> --}}
                                <a href="{{ $course->card_image }}" target="_blank">Click to see previous uploaded image</a>
                                @if($errors->has('course_card_image'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_card_image') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="promo_image">Promotional Image (710 * 488)</label>
                                {{-- <input class="form-control mb-3 {{ $errors->has('promo_image') ? 'is-invalid' : '' }}" type="file" name="promo_image" id="promo_image" accept="image/*"> --}}
                                <a href="{{ $course->promotional_image }}" target="_blank">Click to see previous uploaded image</a>
                                @if($errors->has('promo_image'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('promo_image') }}
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="" for="promo_video">Promotional Video</label>
                                {{-- <input class="form-control {{ $errors->has('promo_video') ? 'is-invalid' : '' }}" type="text" name="promo_video" id="promo_video" value="{{ old('promo_video', $course->promotional_video ?? '') }}" placeholder="Ex: https://www.youtube.com/watch?v=nA1A..., https://youtu.be/nA1Aqp..."> --}}
                                <a href="{{ $course->promotional_video }}" target="_blank">Click to see previous uploaded video</a>
                                @if($errors->has('promo_video'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('promo_video') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="course-status-section">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Course Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="required" for="course_status">Course status</label>
                                <select class="form-control {{ $errors->has('course_status') ? 'is-invalid' : '' }}" name="course_status" id="course_status" disabled>
                                    <option value="">Select</option>
                                    @foreach ($courseStatus as $key => $label)
                                        @php
                                            // Check if `old('status')` is null, and if so, set it to a -1
                                            $oldStatus = old('course_status') !== null ? old('course_status') : -1;
                                            $isSelected = $oldStatus == $key || (isset($course) && $course->status == $key);
                                        @endphp
                                        <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('course_status'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('course_status') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
    @parent

    @include('layouts.common.ckeditor5.43_3_0')
    {{-- @include('layouts.common.ckeditor5.super_build_41_2_1') --}}

    @include('teacher.courses.scripts.common')

    <script>
        $(document).ready(function() {
            var contentTitles = @json($contentTitles);
            var contentDescriptions = @json($contentDescriptions);

            if (contentTitles.length > 0) {
                for (var i = 0; i < contentTitles.length; i++) {
                    if (i == 0) {
                        const firstCard = document.getElementById('content-card-template');
                        const firstTitleInput = firstCard.querySelector('input[type="text"]');
                        const firstDescriptionTextarea = firstCard.querySelector('textarea');
                        // Set the values for the first card
                        firstTitleInput.value = contentTitles[0];
                        firstDescriptionTextarea.value = contentDescriptions[0];
                    } else {
                        addCard(contentTitles[i], contentDescriptions[i]);
                    }
                }
            }
        });
    </script>
@endsection
