<!DOCTYPE html>
<html data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

    <head>
        <title>Sign In</title>
        @include('layouts.common.title-meta')
        @include('layouts.common.head-css')
        <style>
            .c-app {
                /* color: #3c4b64; */
                background-color: #ebedef;
                /* --color: #3c4b64; */
                display: -ms-flexbox;
                display: flex;
                -ms-flex-direction: row;
                flex-direction: row;
                min-height: 100vh;
            }

            .flex-row {
                -ms-flex-direction: row !important;
                flex-direction: row !important;
            }

            .align-items-center {
                -ms-flex-align: center !important;
                align-items: center !important;
            }
        </style>
    </head>
    <body>
        <div class="auth-page-content c-app flex-row align-items-center">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="">Sign in to continue to the System.</p>
                                </div>

                                @if(session('crudMsg'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>{{ session('crudMsg') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="p-2 mt-4">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="username" class="form-label">Email</label>
                                            <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">
                                            @if ($errors->has('email'))
                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            {{-- <div class="float-end">
                                                <a href="auth-pass-reset-basic" class="text-muted">Forgot password?</a>
                                            </div> --}}
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} password-input" required placeholder="{{ trans('global.login_password') }}">
                                                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-toggle-btn" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                @if ($errors->has('password'))
                                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100" type="submit">{{ trans('global.login') }}</button>
                                        </div>

                                        {{-- <div class="mt-4 text-center">
                                            <div class="signin-other-title">
                                                <h5 class="fs-13 mb-4 title">Sign In with</h5>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-facebook-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-google-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-dark btn-icon waves-effect waves-light"><i class="ri-github-fill fs-16"></i></button>
                                                <button type="button" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-twitter-fill fs-16"></i></button>
                                            </div>
                                        </div> --}}
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="mt-4 text-center">
                            <p class="mb-0">Don't have an account ? <a href="register" class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.common.vendor-scripts') --}}

        <!-- JAVASCRIPT -->
        <script src="{!! asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
        <script src="{!! asset('theme/admin/assets/libs/simplebar/simplebar.min.js') !!}"></script>
        <script src="{!! asset('theme/admin/assets/libs/node-waves/waves.min.js') !!}"></script>
        <script src="{!! asset('theme/admin/assets/libs/feather-icons/feather.min.js') !!}"></script>
        <script src="{!! asset('theme/admin/assets/js/pages/plugins/lord-icon-2.1.0.js') !!}"></script>
        <script src="{!! asset('theme/admin/assets/js/plugins.js') !!}"></script>

        <!-- password-addon init -->
        <script src="{!! asset('theme/admin/assets/js/pages/password-addon.init.js') !!}"></script>

        @include('layouts.common.toggle-password-visibility')

    </body>
</html>
