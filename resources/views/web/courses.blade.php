@extends('web.include.master')
@section('content')

    <div class="rbt-page-banner-wrapper">
        <!-- Start Banner BG Image  -->
        <div class="rbt-banner-image"></div>
        <!-- End Banner BG Image  -->
        <div class="rbt-banner-content">
            <!-- Start Banner Content Top  -->
            <div class="rbt-banner-content-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Start Breadcrumb Area  -->
                            <ul class="page-list">
                                <li class="rbt-breadcrumb-item"><a href="{{ route('home') }}" style="color: #000000;">Home</a></li>
                                <li>
                                    <div class="icon-right"><i class="feather-chevron-right"></i></div>
                                </li>
                                <li class="rbt-breadcrumb-item active" style="color: #000000;">All Courses</li>
                            </ul>
                            <!-- End Breadcrumb Area  -->

                            <div class=" title-wrapper">
                                <h1 class="title mb--0">All Courses</h1>
                                <a href="#" class="rbt-badge-2" style="background: rgb(237 173 134 / 80%);">
                                    <div class="image">🎉</div> {{ $totalCourses }} Courses
                                </a>
                            </div>

                            {{-- <p class="description">Courses that help beginner designers become true unicorns. </p> --}}
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Banner Content Top  -->
            <!-- Start Course Top  -->
            <div class="rbt-course-top-wrapper mt--40">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-lg-5 col-md-12">
                            <div class="rbt-sorting-list d-flex flex-wrap align-items-center">
                                <div class="rbt-short-item switch-layout-container">
                                    <ul class="course-switch-layout">
                                        <li class="course-switch-item"><button class="rbt-grid-view active" title="Grid Layout"><i class="feather-grid"></i> <span class="text">Grid</span></button></li>
                                        <li class="course-switch-item"><button class="rbt-list-view" title="List Layout"><i class="feather-list"></i> <span class="text">List</span></button></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Course Top  -->
        </div>
    </div>

    <!-- Start Card Style -->
    <div class="rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row row--30 gy-5">
                <div class="col-lg-4 order-2 order-lg-1">
                    <aside class="rbt-sidebar-widget-wrapper">
                        <form method="GET" action="{{ route('courses') }}" class="rbt-search-style-1" id="filter-form">
                            <!-- Start Widget Area  -->
                            <div class="rbt-single-widget rbt-widget-search">
                                <div class="inner">
                                    <input type="text" placeholder="Search Courses" name="course_title" id="course_title" value="{{ request('course_title') }}">
                                </div>
                            </div>
                            <!-- End Widget Area  -->

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

                            <!-- Start Rating Widget Area  -->
                            {{-- <div class="rbt-single-widget rbt-widget-rating">
                                <div class="inner">
                                    <h4 class="rbt-widget-title">Ratings</h4>
                                    <ul class="rbt-sidebar-list-wrapper rating-list-check">
                                        @foreach ($ratings as $rating)
                                            <li class="rbt-check-group">
                                                <input id="cat-radio-{{ $rating->star }}" type="radio" name="course_rating[]" value="{{ $rating->star }}" {{ in_array($rating->star, request('course_rating', [])) ? 'checked' : '' }}>
                                                <label for="cat-radio-{{ $rating->star }}">
                                                    <span class="rating">
                                                        <!-- Generate stars dynamically -->
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $rating->star ? '' : 'off' }}"></i>
                                                        @endfor
                                                    </span>
                                                    <span class="rbt-lable count">{{ $rating->ratings_count }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div> --}}
                            <!-- End Rating Widget Area  -->

                            <!-- Start Instructors Widget Area  -->
                            <div class="rbt-single-widget rbt-widget-instructor">
                                <div class="inner">
                                    <h4 class="rbt-widget-title">Instructors</h4>
                                    <ul class="rbt-sidebar-list-wrapper instructor-list-check">
                                        @foreach ($teachers as $key => $data)
                                            <li class="rbt-check-group">
                                                <input id="ins-list-{{ $data->user_id }}" type="checkbox" name="course_instructors[]" value="{{ $data->user_id }}" {{ in_array($data->user_id, request('course_instructors', [])) ? 'checked' : '' }}>
                                                <label for="ins-list-{{ $data->user_id }}">
                                                    {{ $data->first_name . " " . $data->last_name }}
                                                    <span class="rbt-lable count">{{ $data->courses_count }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- End Instructors Widget Area  -->

                            <div class="rbt-single-widget rbt-widget-search">
                                <div class="inner">
                                    <button type="submit" class="btn btn-primary btn-lg">Search</button>
                                    <button class="btn btn-warning btn-lg">Clear</button>
                                </div>
                            </div>
                        </form>
                    </aside>
                </div>
                <div class="col-lg-8 order-1 order-lg-2">
                    <div class="rbt-course-grid-column">
                        <!-- Start Single Card  -->
                        @foreach ($courses as $key => $data)
                            <div class="course-grid-2">
                                <div class="rbt-card variation-01 rbt-hover">
                                    <div class="rbt-card-img">
                                        <a href="{{ route('course-details', $data->id) }}">
                                            <img src="{{ $data->card_image }}" alt="Card image">
                                            {{-- <div class="rbt-badge-3 bg-white">
                                                <span>-{{ $data->discount_amount . App\Models\Course::TYPE_ARRAY[$data->discount_type] }}</span>
                                                <span>Off</span>
                                            </div> --}}
                                        </a>
                                    </div>
                                    <div class="rbt-card-body">
                                        {{-- <div class="rbt-card-top">
                                            <div class="rbt-bookmark-btn">
                                                <a class="rbt-round-btn" title="Bookmark" href="{{ route('wishlist.store', $data->id) }}"><i class="feather-bookmark"></i></a>
                                            </div>
                                            <div class="rbt-review">
                                                <a class="rbt-round-btn left-icon" title="Add To Cart" href="{{ route('cart.store', $data->id) }}"><i class="feather-shopping-cart"></i></a>
                                            </div>
                                        </div> --}}

                                        <h4 class="rbt-card-title"><a href="{{ route('course-details', $data->id) }}">{{ $data->title }}</a>
                                        </h4>

                                        <ul class="rbt-meta">
                                            <li><i class="feather-book"></i>{{ $data->total_class }} Lessons</li>
                                            <li><i class="feather-users"></i>{{ $data->courseStudents->count() }} Students</li>
                                        </ul>

                                        <p class="rbt-card-text">{!! $data->short_description !!}</p>
                                        <div class="rbt-author-meta mb--10">
                                            <div class="rbt-avater">
                                                <a href="#">
                                                    <img src="{{ $data->courseTeacher->image }}" alt="{{ $data->courseTeacher->first_name }}">
                                                </a>
                                            </div>
                                            <div class="rbt-author-info">
                                                By <a href="{{ route('instructor-profile', $data->courseTeacher->user_id) }}">{{ $data->courseTeacher->first_name }}</a>
                                            </div>
                                        </div>
                                        <div class="rbt-card-bottom">
                                            <div class="rbt-price">
                                                <span class="current-price">{{ $data->discounted_price ?? $data->price }} CREDIT</span>
                                                @if ($data->discounted_price)
                                                    <span class="off-price">{{ $data->price }} CREDIT</span>
                                                @endif
                                            </div>
                                            <a class="rbt-btn-link" href="{{ route('course-details', $data->id) }}">Learn
                                                More<i class="feather-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- End Single Card  -->

                    </div>

                    @if ($courses->count() > 0)
                        <div class="row">
                            <div class="col-lg-12 mt--60">
                                <nav>
                                    {{-- {{ $courses->withQueryString()->links('pagination::bootstrap-5') }} --}}
                                    {{ $courses->withQueryString()->links('web.include.paginator') }}
                                </nav>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Card Style -->

@endsection
