@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.create') }} About section 2</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.about-us.section-2.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="account-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Section 2 Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="title">Title</label>
                                            <input type="hidden" name="section_id" value={{ App\Models\AboutUsContents::APPLICATION_PROCEDURE }}>
                                            <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('title') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="description">Description</label>
                                            <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description', '') }}</textarea>

                                            @if($errors->has('description'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('description') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="icon_image">Icon Image (128 * 128)</label>
                                            <input class="form-control {{ $errors->has('icon_image') ? 'is-invalid' : '' }}" type="file" name="icon_image" id="icon_image">
                                            @if($errors->has('icon_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('icon_image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 text-start">
                        <button class="btn btn-primary" type="submit" id="create-button">
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
    {{-- @include('layouts.common.ckeditor5.super_build_41_2_1') --}}

@endsection
