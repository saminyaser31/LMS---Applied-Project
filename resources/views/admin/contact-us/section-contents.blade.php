@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Contact Section</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.contact-us.update") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="Contact-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Contact Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="email_1">Primary Email</label>
                                            <input id="email_1" name="email_1" type="text" class="form-control {{ $errors->has('email_1') ? ' is-invalid' : '' }}" value="{{ old('email_1', $contactUs->email_1 ?? '') }}" required>
                                            @if($errors->has('email_1'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email_1') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="email_2">Secondary Email</label>
                                            <input id="email_2" name="email_2" type="text" class="form-control {{ $errors->has('email_2') ? ' is-invalid' : '' }}" value="{{ old('email_2', $contactUs->email_2 ?? '') }}">
                                            @if($errors->has('email_2'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email_2') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="phone_no_1">Primary Phone No</label>
                                            <input class="form-control {{ $errors->has('phone_no_1') ? 'is-invalid' : '' }}" type="text" name="phone_no_1" id="phone_no_1" value="{{ old('phone_no_1', $contactUs->phone_no_1 ?? '') }}" required>
                                            @if($errors->has('phone_no_1'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('phone_no_1') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="phone_no_2">Secondary Phone No</label>
                                            <input class="form-control {{ $errors->has('phone_no_2') ? 'is-invalid' : '' }}" type="text" name="phone_no_2" id="phone_no_2" value="{{ old('phone_no_2', $contactUs->phone_no_2 ?? '') }}">
                                            @if($errors->has('phone_no_2'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('phone_no_2') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="location_1">Primary Location</label>
                                            <input class="form-control {{ $errors->has('location_1') ? 'is-invalid' : '' }}" type="text" name="location_1" id="location_1" value="{{ old('location_1', $contactUs->location_1 ?? '') }}" required>
                                            @if($errors->has('location_1'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('location_1') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="location_2">Secondary Location</label>
                                            <input class="form-control {{ $errors->has('location_2') ? 'is-invalid' : '' }}" type="text" name="location_2" id="location_2" value="{{ old('location_2', $contactUs->location_2 ?? '') }}">
                                            @if($errors->has('location_2'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('location_2') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12" id="form-section-info-section">
                        <div class="card" id="content-card-template">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Form Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="title">Title</label>
                                            <input id="title" name="title" type="text" class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title', $contactUs->title ?? '') }}" required>
                                            @if($errors->has('title'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('title') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="form_title">Form Title</label>
                                            <input class="form-control {{ $errors->has('form_title') ? 'is-invalid' : '' }}" type="text" name="form_title" id="form_title" value="{{ old('form_title', $contactUs->form_title ?? '') }}" required>
                                            @if($errors->has('form_title'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('form_title') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="form_subtitle">Form Subtitle</label>
                                            <input type="text" class="form-control {{ $errors->has('form_subtitle') ? 'is-invalid' : '' }}" name="form_subtitle" id="form_subtitle" value="{{ old('form_subtitle', $contactUs->form_subtitle ?? '') }}" required>
                                            @if($errors->has('form_subtitle'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('form_subtitle') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="form_image">Form Image (500 * 550)</label>
                                            <input class="form-control mb-3 {{ $errors->has('form_image') ? 'is-invalid' : '' }}" type="file" name="form_image" id="form_image" accept="image/*">
                                            <a href="{{ $contactUs->form_image ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('form_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('form_image') }}
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

@endsection
