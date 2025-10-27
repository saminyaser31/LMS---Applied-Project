@extends('web.include.master')
@section('content')

    <!-- Start breadcrumb Area -->
    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Instructor</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li>
                                <div class="icon-right"><i class="feather-chevron-right"></i></div>
                            </li>
                            <li class="rbt-breadcrumb-item active">Instructor</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area -->

    <div class="rbt-team-area bg-color-extra2 rbt-section-gap" style="background:#f38b051f;">
        <div class="container">
            <div class="row row--30 gy-5">

                <div class="col-lg-4 order-2 order-lg-1">
                    <aside class="rbt-sidebar-widget-wrapper">
                        <form method="GET" action="{{ route('instructors') }}" class="rbt-search-style-1" id="filter-form">
                            <!-- Start Course Categories Widget Area  -->
                            <div class="rbt-single-widget rbt-widget-categories has-show-more">
                                <div class="inner">
                                    <h4 class="rbt-widget-title">Categories</h4>
                                    <ul class="rbt-sidebar-list-wrapper categories-list-check has-show-more-inner-content">
                                        @foreach ($courseCategories as $key => $data)
                                            <li class="rbt-check-group">
                                                <input id="category-{{ $data->id }}" type="checkbox" name="course_categories[]" value="{{ $data->id }}" {{ in_array($data->id, request('course_categories', [])) ? 'checked' : '' }}>
                                                <label for="category-{{ $data->id }}">{{ $data->name }} <span class="rbt-lable count">{{ $data->courses_count }}</span></label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="rbt-show-more-btn">Show More</div>
                            </div>
                            <!-- End Course Categories Widget Area  -->

                            <!-- Start Course Subjects Widget Area  -->
                            <div class="rbt-single-widget rbt-widget-categories has-show-more">
                                <div class="inner">
                                    <h4 class="rbt-widget-title">Subjects</h4>
                                    <ul class="rbt-sidebar-list-wrapper categories-list-check has-show-more-inner-content">
                                        @foreach ($courseSubjects as $key => $data)
                                            <li class="rbt-check-group">
                                                <input id="subject-{{ $data->id }}" type="checkbox" name="course_subjects[]" value="{{ $data->id }}" {{ in_array($data->id, request('course_subjects', [])) ? 'checked' : '' }}>
                                                <label for="subject-{{ $data->id }}">{{ $data->name }} <span class="rbt-lable count">{{ $data->courses_count }}</span></label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="rbt-show-more-btn">Show More</div>
                            </div>
                            <!-- End Course Subjects Widget Area  -->

                            <!-- Start Course Levels Widget Area  -->
                            <div class="rbt-single-widget rbt-widget-categories has-show-more">
                                <div class="inner">
                                    <h4 class="rbt-widget-title">Levels</h4>
                                    <ul class="rbt-sidebar-list-wrapper categories-list-check has-show-more-inner-content">
                                        @foreach ($courseLevels as $key => $data)
                                            <li class="rbt-check-group">
                                                <input id="level-{{ $data->id }}" type="checkbox" name="course_levels[]" value="{{ $data->id }}" {{ in_array($data->id, request('course_levels', [])) ? 'checked' : '' }}>
                                                <label for="level-{{ $data->id }}">{{ $data->name }} <span class="rbt-lable count">{{ $data->courses_count }}</span></label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- End Course Levels Widget Area  -->

                            <div class="rbt-single-widget rbt-widget-search">
                                <div class="inner">
                                    <button type="submit" class="btn btn-primary btn-lg">Search</button>
                                    <a class="btn btn-warning btn-lg" href="{{ route('instructors') }}">Clear</a>
                                </div>
                            </div>
                        </form>
                    </aside>
                </div>

                <div class="col-lg-8 order-1 order-lg-2">
                    <div class="row">
                        @foreach($teachers as $key => $data)
                            <!-- Start Single Team  -->
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="rbt-team team-style-default style-three small-layout rbt-hover">
                                    <a href="{{ route('instructor-profile', $data->user_id) }}">
                                        <div class="inner">
                                            <div class="thumbnail"><img src="{{ $data->image }}" alt="{{ $data->first_name . ' ' . $data->last_name }}"></div>
                                            <div class="content">
                                                <h4 class="title">{{ $data->first_name . ' ' . $data->last_name }}</h4>
                                                {{-- <h6 class="subtitle theme-gradient"></h6>
                                                <span class="team-form">
                                                    <i class="feather-map-pin"></i>
                                                    <span class="location"></span>
                                                </span> --}}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if ($teachers->count() > 0)
                <div class="row">
                    <div class="col-lg-12 mt--60">
                        <nav>
                            {{ $teachers->withQueryString()->links('web.include.paginator') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
