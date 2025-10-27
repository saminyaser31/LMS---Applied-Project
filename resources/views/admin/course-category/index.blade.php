@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Category {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-12">
                    <div style="margin-bottom: 10px;" class="row">
                        <div class="col-lg-12 text-end">
                            <a class="btn btn-success" href="{{ route('admin.course-category.create') }}">
                                {{ trans('global.add') }} Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Course Category- {{ $totalCourseCategories }}
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($courseCategories->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($courseCategories as $key => $data)
                                        <tr>
                                            <td>{{ ($courseCategories->currentPage() - 1) * $courseCategories->perPage() + $loop->iteration }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->createdBy ? $data->createdBy->email : null }}</td>
                                            <td>{{ $data->updatedBy ? $data->updatedBy->email : null }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-info mt-2"
                                                    href="{{ route('admin.course-category.edit', $data->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>

                                                {{-- <a class="btn btn-sm btn-danger mt-2" onclick="return confirm('{{ trans('global.areYouSure') }}');"
                                                    href="{{ route('admin.course-category.delete', $data->id) }}">
                                                    {{ trans('global.delete') }}
                                                </a> --}}
                                            </td>
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
                        {{ $courseCategories->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    @parent
    <script>
    </script>
@endsection
