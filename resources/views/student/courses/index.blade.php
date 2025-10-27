@extends('layouts.common.master')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Course {{ trans('global.list') }}</h4>
                    </div>
                </div>
            </div>

            @include('layouts.common.session-message')

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-lg-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0 fw-semibold fs-16">Total Course- {{ $totalCourses }}</h5>
                        </div>
                        {{-- <div class="flex-shrink-0 mt-4 mt-lg-0">
                            <a href="#" class="btn btn-soft-primary">View All <i class="ri-arrow-right-line align-bottom"></i></a>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="row row-cols-xl-3 row-cols-lg-4 row-cols-md-6 row-cols-1">
                @if (!($enrolledCourses->isEmpty()))
                    @foreach ($enrolledCourses as $key => $data)
                        <div class="col">
                            <div class="card explore-box card-animate">
                                <div class="explore-place-bid-img">
                                    <img src="{{ $data->courses->promotional_image }}" alt="" class="card-img-top explore-img" />
                                    <div class="bg-overlay"></div>
                                    <div class="place-bid-btn">
                                        <a href="{{ route('student.courses.show', $data->courses->id) }}" class="btn btn-success"><i class="mdi-view-agenda align-bottom me-1"></i> View Details</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="mb-1"><a href="{{ route('student.courses.show', $data->courses->id) }}">{{ Str::limit($data->courses->title, 35) }}</a></h5>
                                    <p class="text-muted mb-0">{{ $data->courses->courseTeacher->first_name . " " . $data->courses->courseTeacher->last_name }}</p>
                                </div>
                                <div class="card-footer border-top border-top-dashed">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 fs-14">
                                            <i class="ri-book-fill text-warning align-bottom me-1"></i> Lessons: <span class="fw-medium">{{ $data->courses->total_class }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h5 class="text-center">Not available</h5>
                @endif
            </div>

            {{ $enrolledCourses->withQueryString()->links('pagination::bootstrap-5') }}

        </div>
    @endsection
    @section('scripts')
        @parent
        <script>
        </script>
    @endsection
