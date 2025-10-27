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
                        <div class="card-header">
                            {{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                                    <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                        type="text" name="title" id="title" value="{{ old('title', '') }}"
                                        required>
                                    @if ($errors->has('title'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('title') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="required"
                                        for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                                    {{-- <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all"
                                            style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all"
                                            style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div> --}}
                                    <select
                                        class="js-example-basic-multiple {{ $errors->has('permissions') ? 'is-invalid' : '' }}"
                                        name="permissions[]" id="permissions" multiple required>
                                        @foreach ($permissions as $id => $permission)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>
                                                {{ $permission }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('permissions'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('permissions') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.role.fields.permissions_helper') }}</span>
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
