@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">{{ trans('global.show') }} Profile</h4>
                    </div>
                </div>
            </div>

            <div class="row">
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
                                            <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email', $teacher->email ?? '') }}" autocomplete="email" autofocus disabled disabled>
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
                                            <input class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}" type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', $teacher->phone_no ?? '') }}" disabled>
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
                                            <input id="first_name" name="first_name" type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" value="{{ old('first_name', $teacher->first_name ?? '') }}" autofocus disabled>
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
                                            <input class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name" id="last_name" value="{{ old('last_name', $teacher->last_name ?? '') }}" disabled>
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
                                            <input type="date" class="form-control {{ $errors->has('dob') ? 'is-invalid' : '' }}" name="dob" id="dob" value="{{ old('dob', $teacher->dob ?? '') }}" disabled>
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
                                            <select class="form-control search select2 {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender" disabled>
                                                <option value="">Select Gender</option>
                                                @foreach(App\Helper\Helper::getAllGender() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('gender') == $id || $teacher->gender == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                            <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" disabled>{{ old('address', $teacher->address ?? '') }}</textarea>
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
                                            <select class="form-control search select2 {{ $errors->has('marital_status') ? 'is-invalid' : '' }}" name="marital_status" id="marital_status" disabled>
                                                <option value="">Select Status</option>
                                                @foreach(App\Helper\Helper::getAllMaritalStatus() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('marital_status') == $id || $teacher->marital_status == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                            <select class="form-control search select2 {{ $errors->has('religion') ? 'is-invalid' : '' }}" name="religion" id="religion" disabled>
                                                <option value="">Select Religion</option>
                                                @foreach(App\Helper\Helper::getAllReligion() as $id => $data)
                                                    <option value="{{ $id }}" {{ (old('religion') == $id || $teacher->religion == $id) ? 'selected' : '' }}>{{ $data }}</option>
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
                                            <label class="" for="image">Image (415 * 555)</label>
                                            <input class="form-control mb-3 {{ $errors->has('image') ? 'is-invalid' : '' }}" type="file" name="image" id="image" accept="image/*" disabled>
                                            <a href="{{ $teacher->image }}" target="_blank">Click to see previous uploaded image</a>
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
                                            <input class="form-control mb-3 {{ $errors->has('cover_image') ? 'is-invalid' : '' }}" type="file" name="cover_image" id="cover_image" accept="image/*" disabled>
                                            <a href="{{ $teacher->cover_image }}" target="_blank">Click to see previous uploaded image</a>
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
                                            <input id="nid_no" name="nid_no" type="text" class="form-control {{ $errors->has('nid_no') ? ' is-invalid' : '' }}" value="{{ old('nid_no', $teacher->nid_no ?? '') }}" disabled>
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
                                            <input class="form-control mb-3 {{ $errors->has('nid_front_image') ? 'is-invalid' : '' }}" type="file" name="nid_front_image" id="nid_front_image" accept="image/*" disabled>
                                            <a href="{{ $teacher->nid_front_image }}" target="_blank">Click to see previous uploaded image</a>
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
                                            <input class="form-control mb-3 {{ $errors->has('nid_back_image') ? 'is-invalid' : '' }}" type="file" name="nid_back_image" id="nid_back_image" accept="image/*" disabled>
                                            <a href="{{ $teacher->nid_back_image }}" target="_blank">Click to see previous uploaded image</a>
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
                                            <input id="experience" name="experience" type="text" class="form-control {{ $errors->has('experience') ? ' is-invalid' : '' }}" value="{{ old('experience', $teacher->experience ?? '') }}" disabled>
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
                                            <textarea class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" name="bio" id="bio" disabled>{{ old('bio', $teacher->bio ?? '') }}</textarea>
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
                                            <textarea class="form-control {{ $errors->has('detailed_info') ? 'is-invalid' : '' }} ckeditor-classic" name="detailed_info" id="detailed_info" disabled>{{ old('detailed_info', $teacher->detailed_info ?? '') }}</textarea>
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
            </div>
        </div>
@endsection

@section('scripts')
    @parent

    {{-- @include('layouts.common.ckeditor5.43_3_0') --}}
    @include('layouts.common.ckeditor5.super_build_41_2_1')

@endsection
