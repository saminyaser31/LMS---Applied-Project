@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Contact Messages</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Message List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($contactMessages->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            {{-- <th>Sttaus</th> --}}
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($contactMessages as $key => $contactMessage)
                                        <tr>
                                            {{-- <td>{{ $contactMessages->firstItem() + $key }}</td> --}}
                                            <td>{{ ($contactMessages->currentPage() - 1) * $contactMessages->perPage() + $loop->iteration }}</td>
                                            <td>{{ $contactMessage->name }}</td>
                                            <td>{{ $contactMessage->email }}</td>
                                            <td>{{ $contactMessage->phone_no }}</td>
                                            <td>{{ $contactMessage->subject }}</td>
                                            <td>{{ $contactMessage->message }}</td>
                                            {{-- <td>{{ ($contactMessage->status) ? $contactMessage->createdBy->email : '' }}</td> --}}
                                            {{-- <td>
                                                <a class="btn btn-sm btn-primary mt-2"
                                                    href="{{ route('teacher.courses.show', $contactMessage->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('teacher.courses.edit', $contactMessage->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                <form action="{{ route('teacher.courses.delete', $contactMessage->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                                    style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger mt-2" value="{{ trans('global.delete') }}">
                                                </form>
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td rowspan="5">
                                            <h5 class="text-center">Not available</h5>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        {{ $contactMessages->withQueryString()->links('pagination::bootstrap-5') }}
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
