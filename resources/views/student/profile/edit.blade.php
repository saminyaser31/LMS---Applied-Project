@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.edit') }} Profile</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <form method="POST" action="{{ route("student.profile.update", $student->id) }}" enctype="multipart/form-data">
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
                                            <input type="hidden" name="student_id" id="student_id" value="{{ $student->id }}">
                                            <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $student->email ?? '') }}" autocomplete="email" autofocus disabled required>
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
                                            <input class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}" type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', $student->phone_no ?? '') }}" required>
                                            @if($errors->has('phone_no'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('phone_no') }}
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
                                            <input id="first_name" name="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name', $student->first_name ?? '') }}" autofocus required>
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
                                            <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" required>
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
                                            <input type="date" class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" name="dob" id="dob" value="{{ old('dob', $student->dob ?? '') }}" required>
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
                                                    <option value="{{ $id }}" {{ (old('gender') == $id || $student->gender == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                            <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', $student->address ?? '') }}</textarea>
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
                                                    <option value="{{ $id }}" {{ (old('marital_status') == $id || $student->marital_status == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                                    <option value="{{ $id }}" {{ (old('religion') == $id || $student->religion == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                            <a href="{{ $student->image }}" target="_blank">Click to see previous uploaded image</a>
                                            @if($errors->has('image'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->first('image') }}
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

@endsection
