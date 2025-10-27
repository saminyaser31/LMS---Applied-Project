@extends('layouts.common.master')
@section('content')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">{{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                                @if($errors->has('name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                                @if($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                                @if($errors->has('email') && isDeletedEmail(old('email')))
                                    <span>Do you want to restore? <a href="{{ route('admin.users.restoreByEmail', old('email')) }}" class="btn btn-primary">Restore User</a>
                                    </span>
                                @endif
                            </div>

                            <div class="position-relative auth-pass-inputgroup mb-3">
                                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }} password-input" type="password" name="password" id="password" required>
                                <button class="btn btn-link position-absolute end-0 text-decoration-none text-muted password-toggle-btn" type="button" style="top: 43%; box-shadow: none;"><i class="ri-eye-fill align-middle"></i></button>
                                @if($errors->has('password'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                            </div>

                            <div class="mb-3">
                                <div class="form-check {{ $errors->has('approved') ? 'is-invalid' : '' }}">
                                    <input type="hidden" name="approved" value="0">
                                    <input class="form-check-input" type="checkbox" name="approved" id="approved" value="1" {{ old('approved', 0) == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="approved">{{ trans('cruds.user.fields.approved') }}</label>
                                </div>
                                @if($errors->has('approved'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('approved') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.approved_helper') }}</span>
                            </div>
                            <div class="mb-3">
                                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                                {{-- <div style="padding-bottom: 4px">
                                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                </div> --}}
                                <select class="js-example-basic-multiple {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                                    <option value="">Select</option>
                                    @foreach($roles as $id => $role)
                                        <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('roles'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('roles') }}
                                    </div>
                                @endif
                                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                            </div>
                            <div class="mb-3 text-start">
                                <button class="btn btn-primary" type="submit">
                                    {{ trans('global.save') }}
                                </button>
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

    @include('layouts.common.toggle-password-visibility')

@endsection
