@extends('layouts.common.master')
@section('content')
    <style>
        .styled-table {
            white-space: nowrap;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #101e34;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .btn-transparent-outline {
            background-color: transparent;
            border-color: #13c542;
            border-width: 1px;
        }

        @media (max-width: 767px) {
            .couponAddBtn {
                float: left!important;
            }
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
            background-color: #f8f9fa;
            font-size: 14px;
        }
        .pagination a.active, .pagination a:hover {
            background-color: #007bff;
            color: white;
        }
        .pagination a.disabled {
            color: #6c757d;
            pointer-events: none;
            cursor: not-allowed;
        }
        .pagination a:first-child, .pagination a:last-child {
            margin: 0;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Order {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Total Order- {{ $totalOrders }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order List</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered dt-responsive table-striped align-middle"
                                style="width:100%">
                                @if (!($orders->isEmpty()))
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Courses</th>
                                            <th>Order type</th>
                                            <th>Transaction ID</th>
                                            <th>Total</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filteredData">
                                        @foreach ($orders as $key => $data)
                                        <tr>
                                            {{-- <td>{{ $orders->firstItem() + $key }}</td> --}}
                                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm ml-2 view-courses" data-courses="{{ $data->courseEnrollment }}">View Courses</button>
                                            </td>
                                            <td>{{ App\Models\Order::ORDER_TYPE_SELECT[$data->order_type] }}</td>
                                            <td>{{ $data->transaction_id }}</td>
                                            <td>{{ $data->total }}</td>
                                            <td>{{ $data->grand_total }}</td>
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
                        {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Course List Modal -->
        <div id="EnrolledCourseModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style="max-width: 600px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enrolled Courses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                    </div>
                    <div class="modal-body table-responsive">
                        <table class="table table-bordered table-striped table-new styled-table-new" style="overflow: scroll;" id="country-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody id="enrolledCourseListBody">
                            </tbody>
                        </table>
                        <div id="course-table-pagination-links" data-source="" class="pagination"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('scripts')
        @parent
        <script>
            $(document).ready(function() {
                function renderTable(page, data, type, perPage=10) {
                    var start = (page - 1) * perPage;
                    var end = start + perPage;

                    if (type == 'course') {
                        var paginatedCourses = data.slice(start, end);

                        var tableBody = '';
                        $.each(paginatedCourses, function(index, data) {
                            tableBody += '<tr>';
                            tableBody += '<td>' + (++index) + '</td>';
                            tableBody += '<td>' + data.courses.title + '</td>';
                            tableBody += '<td>' + data.courses.course_teacher.first_name + ' ' + data.courses.course_teacher.last_name + '</td>';
                            tableBody += '</tr>';
                        });

                        $('#enrolledCourseListBody').html(tableBody);
                    }

                    renderPaginationLinks(page, data, type, perPage);
                }

                function renderPaginationLinks(currentPage, data, type, perPage) {
                    var totalPages = Math.ceil(data.length / perPage);
                    var paginationLinks = '';

                    if (currentPage > 1) {
                        // paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="1">&laquo; First</a> ';
                        paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="' + (currentPage - 1) + '">&lt; Prev</a> ';
                    } else {
                        // paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">&laquo; First</a> ';
                        paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">&lt; Prev</a> ';
                    }

                    // Display page links around the current page
                    var startPage = Math.max(1, currentPage - 2);
                    var endPage = Math.min(totalPages, currentPage + 2);

                    if (startPage > 1) {
                        paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="1">1</a> ';
                        if (startPage > 2) paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">...</a> ';
                    }

                    for (var i = startPage; i <= endPage; i++) {
                        paginationLinks += '<a href="#" class="pagination-link' + (i === currentPage ? ' active' : '') + '" data-type="' + type + '" data-page="' + i + '">' + i + '</a> ';
                    }

                    if (endPage < totalPages) {
                        if (endPage < totalPages - 1) paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">...</a> ';
                        paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="' + totalPages + '">' + totalPages + '</a> ';
                    }

                    if (currentPage < totalPages) {
                        paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="' + (currentPage + 1) + '">Next &gt;</a> ';
                        // paginationLinks += '<a href="#" class="pagination-link" data-type="' + type + '" data-page="' + totalPages + '">Last &raquo;</a> ';
                    } else {
                        paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">Next &gt;</a> ';
                        // paginationLinks += '<a href="#" class="pagination-link disabled" data-type="' + type + '">Last &raquo;</a> ';
                    }

                    if (type == 'course') {
                        $('#course-table-pagination-links').html(paginationLinks);
                    }
                }

                // Handle pagination link clicks
                $(document).on('click', '.pagination-link:not(.disabled)', function(event) {
                    event.preventDefault();
                    currentPage = $(this).data('page');
                    var type = $(this).data('type');
                    if (type == 'course') {
                        var dataSource = $('#course-table-pagination-links').attr('data-source');
                    }
                    var data = JSON.parse(atob(dataSource));
                    renderTable(currentPage, data, type);
                });

                $(document).on('click', '.view-courses', function() {
                    var courses = $(this).data('courses');
                    var perPage = 10;
                    var currentPage = 1;
                    var type = 'course';

                    // Initial render
                    if (courses) {
                        $('#course-table-pagination-links').attr('data-source', btoa(JSON.stringify(courses)));
                        renderTable(currentPage, courses, type, perPage);
                    }

                    // Show the modal
                    $('#EnrolledCourseModal').modal('show');
                });
            });
        </script>
    @endsection
