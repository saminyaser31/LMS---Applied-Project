@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Review {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example"
                                class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($reviews->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Reviewer</th>
                                            <th>Course</th>
                                            <th>Star</th>
                                            <th>Review</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($reviews as $key => $review)
                                        <tr>
                                            {{-- <td>{{ $reviews->firstItem() + $key }}</td> --}}
                                            <td>{{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}</td>
                                            <td>{{ $review }}</td>
                                            <td>{{ $review }}</td>
                                            <td>{{ $review }}</td>
                                            <td>{{ $review }}</td>
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
                        {{ $reviews->withQueryString()->links('pagination::bootstrap-5') }}
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
