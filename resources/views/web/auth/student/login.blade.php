@extends('web.include.master')
@section('content')

    {{-- @include('web.include.breadcrumb') --}}

    <div class="newsletter-style-2 bg-color-primary rbt-section-gap" style="background: linear-gradient(218.15deg, var(--color-secondary) 0%, var(--color-primary) 100%);">
        <div class="container">
            <div class="row gy-5 row--30 justify-content-center">
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1">
                        @if (session('signinErrorMessage'))
                            <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                                <strong>{{ session('signinErrorMessage') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('signinPageMessage'))
                            <div class="alert alert-succcess alert-dismissible fade show my-2" role="alert">
                                <strong>{{ session('signinPageMessage') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <h3 class="title">Login</h3>
                        <form class="max-width-auto" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="col-12 my-3">
                                <div class="rbt-form-group">
                                    <label>{{ trans('global.login_email') }} <span style="color:red">*</span></label>
                                    <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus value="{{ old('email', null) }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 my-3">
                                <div class="rbt-form-group position-relative">
                                    <label>{{ trans('global.login_password') }} <span style="color:red">*</span></label>
                                    <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} password-input" required value="{{ old('password', null) }}">
                                    <button type="button" class="position-absolute translate-middle-y password-toggle-btn" style="right: 10px; top: 70%; border: none; background: none; cursor: pointer;">
                                        <i class="feather-eye"></i>
                                    </button>
                                    @if($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-submit-group">
                                <button type="submit" class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Log In</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                        <div class="text-center mt-5">
                            <a class="rbt-btn-link" href="{{ route('forgot-password.index') }}">Forgot password?</a>
                        </div>
                        <div class="text-center mt-2">
                            <a class="rbt-btn-link" href="{{ route('student.register-page') }}">Register</a>
                        </div>
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
