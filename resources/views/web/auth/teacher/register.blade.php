@extends('web.include.master')
@section('content')
    @section('css')
        @parent
        <style>
            .form-control-file {
                width: 100%;
                height: 50px; /* Match the height of the select input */
                padding: 10px 12px;
                font-size: 16px; /* Adjust to match font size */
            }
            .form-control-file::file-selector-button {
                padding: 6px 12px;
                font-size: 16px;
            }
            input[type=checkbox] ~ label::before {
                display: none;
            }
            input[type=checkbox] {
                opacity: 1;
                position: relative;
            }
        </style>
    @endsection

    {{-- @include('web.include.breadcrumb') --}}

    <div class="newsletter-style-2 bg-color-primary rbt-section-gap" style="background: linear-gradient(218.15deg, var(--color-secondary) 0%, var(--color-primary) 100%);">
        <form class="max-width-auto" method="POST" action="{{ route('teacher.register') }}" enctype="multipart/form-data">
            @csrf

            <div class="container mt-5">
                <div class="row gy-5 row--30 justify-content-center">
                    <div class="col-lg-10">
                        <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                            @if (session('registrationMessage'))
                                <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                                    <strong>{{ session('registrationMessage') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @elseif (session('postRegistrationMessage'))
                                <div class="alert alert-success alert-dismissible fade show my-2" role="alert">
                                    <strong>{{ session('postRegistrationMessage') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <h3 class="title">Account Information</h3>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="email">{{ trans('global.login_email') }} <span style="color:red">*</span></label>
                                        <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus value="{{ old('email', null) }}">
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="phone_no">Phone No <span style="color:red">*</span></label>
                                        <input id="phone_no" name="phone_no" type="text" class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" required autofocus value="{{ old('phone_no', null) }}">
                                        @if($errors->has('phone_no'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('phone_no') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="password">{{ trans('global.login_password') }} <span style="color:red">*</span></label>
                                        <input id="password" name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} password-input" required value="{{ old('password', null) }}">
                                        <button type="button" class="position-absolute translate-middle-y password-toggle-btn" style="left: 45%; top: 76%; border: none; background: none; cursor: pointer;">
                                            <i class="feather-eye"></i>
                                        </button>
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="confirm_password">Confirm {{ trans('global.login_password') }} <span style="color:red">*</span></label>
                                        <input id="confirm_password" name="confirm_password" type="password" class="form-control {{ $errors->has('confirm_password') ? ' is-invalid' : '' }} password-input" required value="{{ old('confirm_password', null) }}">
                                        <button type="button" class="position-absolute translate-middle-y password-toggle-btn" style="right: 7%; top: 76%; border: none; background: none; cursor: pointer;">
                                            <i class="feather-eye"></i>
                                        </button>
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
            </div>

            <div class="container mt-5">
                <div class="row gy-5 row--30 justify-content-center">
                    <div class="col-lg-10">
                        <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                            <h3 class="title">Personal Information</h3>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="firstname">First Name <span style="color:red">*</span></label>
                                        <input id="first_name" name="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" required value="{{ old('first_name', null) }}">
                                        @if($errors->has('first_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('first_name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="lastname">Last Name <span style="color:red">*</span></label>
                                        <input id="last_name" name="last_name" type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" autofocus value="{{ old('last_name', null) }}">
                                        @if($errors->has('last_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('last_name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="lastname">DOB <span style="color:red">*</span></label>
                                        <input id="dob" name="dob" type="date" class="form-control {{ $errors->has('dob') ? ' is-invalid' : '' }}" autofocus value="{{ old('dob', null) }}">
                                        @if($errors->has('dob'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('dob') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="filter-select rbt-modern-select">
                                        <label for="displayname" class="">Gender</label>
                                        <select class="form-control w-100 {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                                            <option value="">Select Gender</option>
                                            @foreach(App\Helper\Helper::getAllGender() as $id => $data)
                                                <option value="{{ $id }}" {{ old('gender') == $id ? 'selected' : '' }}>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('gender'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('gender') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="bio">Address</label>
                                        <textarea id="address" name="address" class="form-control {{ $errors->has('address') ? ' is-invalid' : '' }}" cols="20" rows="5">{{ old('address', null) }}</textarea>
                                        @if($errors->has('address'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="filter-select rbt-modern-select">
                                        <label for="displayname" class="">Marital Sttaus</label>
                                        <select class="form-control w-100 {{ $errors->has('marital_status') ? 'is-invalid' : '' }}" name="marital_status" id="marital_status">
                                            <option value="">Select Status</option>
                                            @foreach(App\Helper\Helper::getAllMaritalStatus() as $id => $data)
                                                <option value="{{ $id }}" {{ old('marital_status') == $id ? 'selected' : '' }}>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('marital_status'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('marital_status') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="filter-select rbt-modern-select">
                                        <label for="displayname" class="">Religion</label>
                                        <select class="form-control w-100 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" id="religion">
                                            <option value="">Select Religion</option>
                                            @foreach(App\Helper\Helper::getAllReligion() as $id => $data)
                                                <option value="{{ $id }}" {{ old('religion') == $id ? 'selected' : '' }}>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('religion'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('religion') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="image">Image (1500 * 1500) <span style="color:red">*</span></label>
                                        <input type="file" class="form-control form-control-file {{ $errors->has('image') ? ' is-invalid' : '' }}" name="image" id="image" accept=".jpeg, .jpg, .png, .gif">
                                        @if($errors->has('image'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('image') }}
                                            </div>
                                        @elseif(session('image'))
                                            <div class="text-warning">
                                                Please re-upload the file.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-5">
                <div class="row gy-5 row--30 justify-content-center">
                    <div class="col-lg-10">
                        <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                            <h3 class="title">Verification Information</h3>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="lastname">NID No <span style="color:red">*</span></label>
                                        <input id="nid_no" name="nid_no" type="number" class="form-control {{ $errors->has('nid_no') ? ' is-invalid' : '' }}" required autofocus value="{{ old('nid_no', null) }}">
                                        @if($errors->has('nid_no'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('nid_no') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="nid_front_image">NID Front Image</label>
                                        <input type="file" class="form-control form-control-file {{ $errors->has('nid_front_image') ? ' is-invalid' : '' }}" name="nid_front_image" id="nid_front_image" accept=".jpeg, .jpg, .png, .gif">
                                        @if($errors->has('nid_front_image'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('nid_front_image') }}
                                            </div>
                                        @elseif(session('nid_front_image'))
                                            <div class="text-warning">
                                                Please re-upload the file.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-12 my-3">
                                    <div class="rbt-form-group">
                                        <label for="nid_back_image">NID Back Image</label>
                                        <input type="file" class="form-control form-control-file {{ $errors->has('nid_back_image') ? ' is-invalid' : '' }}" name="nid_back_image" id="nid_back_image" accept=".jpeg, .jpg, .png, .gif">
                                        @if($errors->has('nid_back_image'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('nid_back_image') }}
                                            </div>
                                        @elseif(session('nid_back_image'))
                                            <div class="text-warning">
                                                Please re-upload the file.
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- <div class="row gx-3 gy-4">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <input name="terms_and_condition_agreement" type="checkbox" value="0" class="form-check-input ajax-validation-input " checked>
                                        <label class="form-check-label" for="formCheck1">
                                            I agree to the all terms and conditions
                                        </label>
                                     </div>

                                    <div class="mb-4 col-md-12 col-sm-12 col-xs-12">
                                        <input name="privacy_and_policy_agreement" type="checkbox" value="0" class="form-check-input ajax-validation-input ">
                                        <label class="form-check-label" for="formCheck1">
                                            I agree to the all privacy and policy agreement
                                        </label>
                                    </div>
                                </div> --}}

                                <div class="col-12 mt--20">
                                    <div class="rbt-form-group">
                                        <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse">
                                            <span class="icon-reverse-wrapper">
                                                <span class="btn-text">Register</span>
                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    @parent
    @include('web.include.toggle-password-visibility')
@endsection
