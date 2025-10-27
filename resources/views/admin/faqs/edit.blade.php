@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} FAQ</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.faqs.update", $faq->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="faq-basic-section">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="required" for="title">Title</label>
                                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $faq->title ?? '') }}" required>
                                    @if($errors->has('title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="required" for="description">Description</label>
                                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }} ckeditor-classic" name="description" id="description">{{ old('description', $faq->description ?? '') }}</textarea>

                                    @if($errors->has('description'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('description') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12" id="faq-status-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">FAQ Status</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="required" for="faq_status">Status</label>
                                    <select class="form-control {{ $errors->has('faq_status') ? 'is-invalid' : '' }}" name="faq_status" id="faq_status">
                                        <option value="">Select</option>
                                        @foreach ($faqStatus as $key => $label)
                                            @php
                                                // Check if `old('status')` is null, and if so, set it to a -1
                                                $oldStatus = old('faq_status') !== null ? old('faq_status') : -1;
                                                $isSelected = $oldStatus == $key || (isset($faq) && $faq->status == $key);
                                            @endphp
                                            <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('faq_status'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('faq_status') }}
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
