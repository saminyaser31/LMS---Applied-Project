@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Color Section</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.web.color.update") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="color-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Color Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="primary_color">Primary color</label>
                                            <div class="classic-colorpicker"></div>
                                            <input class="form-control {{ $errors->has('primary_color') ? 'is-invalid' : '' }}" type="text" name="primary_color" id="primary_color" value="{{ old('primary_color', $webColor->primary_color ?? '') }}" required>
                                            @if($errors->has('primary_color'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('primary_color') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="secondary_color">Secondary color</label>
                                            <input class="form-control {{ $errors->has('secondary_color') ? 'is-invalid' : '' }}" type="text" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $webColor->secondary_color ?? '') }}" required>
                                            @if($errors->has('secondary_color'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('secondary_color') }}
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

    <script>
        $(document).ready(function() {
            // Initialize the color pickers
            $('#primary_color').colorpicker({});
            $('#secondary_color').colorpicker();
        });
    </script>

@endsection
