@extends('web.include.master')
@section('content')

    {{-- @include('web.include.breadcrumb') --}}

    <div class="newsletter-style-2 bg-color-primary rbt-section-gap" style="background: linear-gradient(218.15deg, var(--color-secondary) 0%, var(--color-primary) 100%);">
        <div class="container">
            <div class="row gy-5 row--30 justify-content-center">
                <div class="col-lg-6">
                    <div class="rbt-contact-form contact-form-style-1">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show my-2" role="alert">
                                <strong>{{ session('success') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show my-2" role="alert">
                                <strong>{{ session('error') }}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <h3 class="title">Change Password</h3>
                        <form class="max-width-auto" method="POST" action="{{ route('forgot-password.update') }}">
                            @csrf
                            <div class="col-12 my-3">
                                <div class="rbt-form-group">
                                    <label>Email <span style="color:red">*</span></label>
                                    <input id="email" name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus value="{{ old('email', null) }}">
                                    @if($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 my-3">
                                <div class="rbt-form-group">
                                    <label>Token <span style="color:red">*</span></label>
                                    <input id="token" name="token" type="text" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" required autocomplete="token" autofocus value="{{ old('token', null) }}">
                                    @if($errors->has('token'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('token') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 my-3">
                                <div class="rbt-form-group">
                                    <label>Password <span style="color:red">*</span></label>
                                    <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required autocomplete="password" autofocus value="{{ old('password', null) }}">
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
                                        <span class="btn-text">Submit</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                        @if (session('success') && session('route') && session('route') != '')
                            <div class="text-center mt-5">
                                <a class="rbt-btn-link" href="{{ route(session('route')) }}">Login</a>
                            </div>
                        @endif
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
