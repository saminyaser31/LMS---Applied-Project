@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Password</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("student.change-password.update", $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="password-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Password Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <label class="required" for="old_password">Old {{ trans('global.login_password') }}</label>
                                            <input id="old_password" name="old_password" type="password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }} password-input" required placeholder="Old {{ trans('global.login_password') }} *" value="{{ old('old_password', null) }}">
                                            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-toggle-btn" type="button" style="top: 43%; box-shadow: none;"><i class="ri-eye-fill align-middle"></i></button>
                                            @if($errors->has('old_password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('old_password') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <label class="required" for="password">{{ trans('global.login_password') }}</label>
                                            <input id="password" name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} password-input" placeholder="{{ trans('global.login_password') }} *" value="{{ old('password', null) }}" required>
                                            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-toggle-btn" type="button" style="top: 43%; box-shadow: none;"><i class="ri-eye-fill align-middle"></i></button>
                                            @if($errors->has('password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('password') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <label class="required" for="confirm_password">Confirm {{ trans('global.login_password') }}</label>
                                            <input id="confirm_password" name="confirm_password" type="password" class="form-control {{ $errors->has('confirm_password') ? ' is-invalid' : '' }} password-input" placeholder="Confirm {{ trans('global.login_password') }} *" value="{{ old('confirm_password', null) }}" required>
                                            <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-toggle-btn" type="button" style="top: 43%; box-shadow: none;"><i class="ri-eye-fill align-middle"></i></button>
                                            @if($errors->has('confirm_password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('confirm_password') }}
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
    @include('layouts.common.toggle-password-visibility')

@endsection
