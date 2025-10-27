@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.create') }} Profile</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("admin.teachers.store") }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12" id="account-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Account Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="email">Email</label>
                                            <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', '') }}" autocomplete="email" autofocus required>
                                            @if($errors->has('email'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('email') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="phone_no">Phone No</label>
                                            <input class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}" type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', '') }}" required>
                                            @if($errors->has('phone_no'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('phone_no') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <label class="required" for="password">{{ trans('global.login_password') }}</label>
                                            <input id="password" name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} password-input" value="{{ old('password', '') }}" autofocus required>
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
                                            <input id="confirm_password" name="confirm_password" type="password" class="form-control {{ $errors->has('confirm_password') ? ' is-invalid' : '' }} password-input" value="{{ old('confirm_password', '') }}" autofocus required>
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

                    <div class="col-lg-12" id="personal-info-section">
                        <div class="card" id="content-card-template">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Personal Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="first_name">First Name</label>
                                            <input id="first_name" name="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name', '') }}" autofocus required>
                                            @if($errors->has('first_name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('first_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="last_name">Last Name</label>
                                            <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', '') }}" required>
                                            @if($errors->has('last_name'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('last_name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="dob">DOB</label>
                                            <input type="date" class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" name="dob" id="dob" value="{{ old('dob', '') }}" required>
                                            @if($errors->has('dob'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('dob') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="gender">Gender</label>
                                            <select class="form-control search select2 {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                @foreach(App\Helper\Helper::getAllGender() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('gender') == $id) ? 'selected' : '' }}>{{ $data }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('gender'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('gender') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="address">Address</label>
                                            <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', '') }}</textarea>
                                            @if($errors->has('address'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('address') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="marital_status">Marital Sttaus</label>
                                            <select class="form-control search select2 {{ $errors->has('marital_status') ? 'is-invalid' : '' }}" name="marital_status" id="marital_status">
                                                <option value="">Select Status</option>
                                                @foreach(App\Helper\Helper::getAllMaritalStatus() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('marital_status') == $id) ? 'selected' : '' }}>{{ $data }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('marital_status'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('marital_status') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="religion">Religion</label>
                                            <select class="form-control search select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" id="religion">
                                                <option value="">Select Religion</option>
                                                @foreach(App\Helper\Helper::getAllReligion() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('religion') == $id) ? 'selected' : '' }}>{{ $data }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('religion'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('religion') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="required" for="image">Image (1500 * 1500)</label>
                                            <input class="form-control mb-3 {{ $errors->has('image') ? 'is-invalid' : '' }}" type="file" name="image" id="image" accept="image/*">
                                            @if($errors->has('image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="cover_image">Cover Image (2610 * 700)</label>
                                            <input class="form-control mb-3 {{ $errors->has('cover_image') ? 'is-invalid' : '' }}" type="file" name="cover_image" id="cover_image" accept="image/*">
                                            @if($errors->has('cover_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('cover_image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12" id="verification-doc-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Verification Requirement</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="email">NID No</label>
                                            <input id="nid_no" name="nid_no" type="text" class="form-control {{ $errors->has('nid_no') ? ' is-invalid' : '' }}" value="{{ old('nid_no', '') }}" required>
                                            @if($errors->has('nid_no'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('nid_no') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="nid_front_image">NID Front Image</label>
                                            <input class="form-control mb-3 {{ $errors->has('nid_front_image') ? 'is-invalid' : '' }}" type="file" name="nid_front_image" id="nid_front_image" accept="image/*">
                                            @if($errors->has('nid_front_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('nid_front_image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="mb-3">
                                            <label class="" for="nid_back_image">NID Back Image</label>
                                            <input class="form-control mb-3 {{ $errors->has('nid_back_image') ? 'is-invalid' : '' }}" type="file" name="nid_back_image" id="nid_back_image" accept="image/*">
                                            @if($errors->has('nid_back_image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('nid_back_image') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12" id="biography-info-section">
                        <div class="card">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Professional & Educational Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="email">Total Experience</label>
                                            <input id="experience" name="experience" type="text" class="form-control {{ $errors->has('experience') ? ' is-invalid' : '' }}" value="{{ old('experience', '') }}" required>
                                            @if($errors->has('experience'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('experience') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="bio">Short Bio</label>
                                            <textarea class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" name="bio" id="bio">{{ old('bio', '') }}</textarea>
                                            @if($errors->has('bio'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('bio') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="required" for="detailed_info">Detailed Information</label>
                                            <textarea class="form-control {{ $errors->has('detailed_info') ? 'is-invalid' : '' }} ckeditor-classic" name="detailed_info" id="detailed_info">{{ old('detailed_info', '') }}</textarea>
                                            @if($errors->has('detailed_info'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('detailed_info') }}
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
    @include('layouts.common.ckeditor5.super_build_41_2_1')
    @include('layouts.common.toggle-password-visibility')

@endsection
