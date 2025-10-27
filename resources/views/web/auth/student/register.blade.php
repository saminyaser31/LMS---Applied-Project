@extends('web.include.master')
@section('content')

    {{-- @include('web.include.breadcrumb') --}}

    <div class="newsletter-style-2 bg-color-primary rbt-section-gap" style="background: linear-gradient(218.15deg, var(--color-secondary) 0%, var(--color-primary) 100%);">
        <div class="container">
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
                        <h3 class="title">Register</h3>
                        <form class="max-width-auto" method="POST" action="{{ route('student.register') }}">
                            @csrf
                            <div class="row">
                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="first_name" name="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" required autofocus placeholder="First Name *" value="{{ old('first_name', null) }}">
                                        {{-- <label>First Name <span style="color:red">*</span></label> --}}
                                        <span class="focus-border"></span>
                                        @if($errors->has('first_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('first_name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="last_name" name="last_name" type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" autofocus placeholder="Last Name *" value="{{ old('last_name', null) }}">
                                        {{-- <label>Last Name <span style="color:red">*</span></label> --}}
                                        <span class="focus-border"></span>
                                        @if($errors->has('last_name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('last_name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }} *" value="{{ old('email', null) }}">
                                        {{-- <label>{{ trans('global.login_email') }} <span style="color:red">*</span></label> --}}
                                        <span class="focus-border"></span>
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="phone_no" name="phone_no" type="text" class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" required autofocus placeholder="Phone No *" value="{{ old('phone_no', null) }}">
                                        {{-- <label>Phone No <span style="color:red">*</span></label> --}}
                                        <span class="focus-border"></span>
                                        @if($errors->has('phone_no'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('phone_no') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="password" name="password" type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }} password-input" required placeholder="{{ trans('global.login_password') }} *" value="{{ old('password', null) }}">
                                        {{-- <label>{{ trans('global.login_password') }} <span style="color:red">*</span></label> --}}
                                        <button type="button" class="position-absolute translate-middle-y password-toggle-btn" style="right: 10px; top: 70%; border: none; background: none; cursor: pointer;">
                                            <i class="feather-eye"></i>
                                        </button>
                                        <span class="focus-border"></span>
                                        @if($errors->has('password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 my-2">
                                    <div class="form-group">
                                        <input id="confirm_password" name="confirm_password" type="password" class="form-control {{ $errors->has('confirm_password') ? ' is-invalid' : '' }} password-input" required placeholder="Confirm {{ trans('global.login_password') }} *" value="{{ old('confirm_password', null) }}">
                                        {{-- <label>Confirm {{ trans('global.login_password') }} <span style="color:red">*</span></label> --}}
                                        <button type="button" class="position-absolute translate-middle-y password-toggle-btn" style="right: 10px; top: 70%; border: none; background: none; cursor: pointer;">
                                            <i class="feather-eye"></i>
                                        </button>
                                        <span class="focus-border"></span>
                                        @if($errors->has('confirm_password'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('confirm_password') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-4 my-2 justify-content-center">
                                <div class="form-submit-group">
                                    <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">Register</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @parent
    @include('web.include.toggle-password-visibility')
@endsection
