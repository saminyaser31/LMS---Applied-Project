@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Hero Section</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.home.hero-section.update") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="account-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Hero Section Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="title">Title</label>
                                            <input type="hidden" name="section_id" value={{ App\Models\HomeContents::HERO_SECTION }}>
                                            <textarea class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }} ckeditor-classic" name="title" id="title">{{ old('title', $heroSection->title ?? '') }}</textarea>
                                            {{-- <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $heroSection->title ?? '') }}" required> --}}
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
                                            <input class="form-control {{ $errors->has('subtitle') ? 'is-invalid' : '' }}" type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $heroSection->subtitle ?? '') }}" required>
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
                                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }} ckeditor-classic" name="description" id="description">{{ old('description', $heroSection->description ?? '') }}</textarea>

                                            @if($errors->has('description'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('description') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="bg_image">Background Image (1920 * 1408)</label>
                                            <input class="form-control mb-3 {{ $errors->has('bg_image') ? 'is-invalid' : '' }}" type="file" name="bg_image" id="bg_image" accept="image/*">
                                            <a href="{{ $webImage->image ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('bg_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('bg_image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div> --}}
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
