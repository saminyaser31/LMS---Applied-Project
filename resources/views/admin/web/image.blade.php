@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Image Section</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.web.image.update") }}" enctype="multipart/form-data">
                    @csrf

                    <div class="col-lg-12" id="image-section">
                        <div class="card" id="content-card-template">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Image Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="logo">Logo (250 * 82)</label>
                                            <input class="form-control mb-3 {{ $errors->has('logo') ? 'is-invalid' : '' }}" type="file" name="logo" id="logo" accept="image/*">
                                            <a href="{{ $webImage->logo ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('logo'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('logo') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="dashboard_logo">Dashboard Logo (250 * 82)</label>
                                            <input class="form-control mb-3 {{ $errors->has('dashboard_logo') ? 'is-invalid' : '' }}" type="file" name="dashboard_logo" id="dashboard_logo" accept="image/*">
                                            <a href="{{ $webImage->dashboard_logo ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('dashboard_logo'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dashboard_logo') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="dashboard_favicon">Dashboard Favicon (150 * 164)</label>
                                            <input class="form-control mb-3 {{ $errors->has('dashboard_favicon') ? 'is-invalid' : '' }}" type="file" name="dashboard_favicon" id="dashboard_favicon" accept="image/*">
                                            <a href="{{ $webImage->dashboard_favicon ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('dashboard_favicon'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dashboard_favicon') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="favicon">Favicon (150 * 164)</label>
                                            <input class="form-control mb-3 {{ $errors->has('favicon') ? 'is-invalid' : '' }}" type="file" name="favicon" id="favicon" accept="image/*">
                                            <a href="{{ $webImage->favicon ?? '#' }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('favicon'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('favicon') }}
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
