@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} About section 1</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.about-us.section-1.update") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="account-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Section 1 Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="title">Title</label>
                                            <input type="hidden" name="section_id" value={{ App\Models\AboutUsContents::ABOUT_DESCRIPTION }}>
                                            <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $section1->title ?? '') }}" required>
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('title') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="subtitle">Subtitle</label>
                                            <input class="form-control {{ $errors->has('subtitle') ? 'is-invalid' : '' }}" type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $section1->subtitle ?? '') }}" required>
                                            @if($errors->has('subtitle'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('subtitle') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="description">Description</label>
                                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }} ckeditor-classic" name="description" id="description">{{ old('description', $section1->description ?? '') }}</textarea>

                                            @if($errors->has('description'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('description') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="image_1">Image 1 (308 * 250)</label>
                                            <input class="form-control {{ $errors->has('image_1') ? 'is-invalid' : '' }}" type="file" name="image_1" id="image_1">
                                            <a href="{{ $section1->image_1 ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('image_1'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('image_1') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="image_2">Image 2 (405 * 490)</label>
                                            <input class="form-control {{ $errors->has('image_2') ? 'is-invalid' : '' }}" type="file" name="image_2" id="image_2">
                                            <a href="{{ $section1->image_2 ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('image_2'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('image_2') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="image_3">Image 3 (396 * 530)</label>
                                            <input class="form-control {{ $errors->has('image_3') ? 'is-invalid' : '' }}" type="file" name="image_3" id="image_3">
                                            <a href="{{ $section1->image_3 ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('image_3'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('image_3') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
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

    {{-- @include('layouts.common.ckeditor5.43_3_0') --}}
    @include('layouts.common.ckeditor5.super_build_41_2_1')

@endsection
