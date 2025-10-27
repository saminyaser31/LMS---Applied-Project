@extends('web.include.master')
@section('css')
    @parent
    <link href="https://vjs.zencdn.net/8.0.4/video-js.css" rel="stylesheet">
    <style>
        .video-js {
            font-size: 16px; /* Customize player text size */
            color: #fff; /* Customize player text color */
        }

        .video-js .vjs-control-bar {
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent control bar */
        }
    </style>
@endsection
@section('content')

    <div class="rbt-page-banner-wrapper">
        <!-- Start Banner BG Image  -->
        <div class="rbt-banner-image"></div>
        <!-- End Banner BG Image  -->
    </div>

    <div class="rbt-dashboard-area rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row">
                @if ($teacher->cover_image)
                    <div class="col-lg-12">
                        <!-- Start Dashboard Top  -->
                        <div class="rbt-dashboard-content-wrapper">
                            <div class="tutor-bg-photo bg_image height-260">
                                <img src="{{ $teacher->cover_image }}" alt="">
                            </div>
                            <!-- Start Tutor Information  -->
                            {{-- <div class="rbt-tutor-information">
                                <div class="rbt-tutor-information-left">
                                    <div class="thumbnail rbt-avatars size-lg">
                                        <img src="assets/images/team/avatar.jpg" alt="Instructor">
                                    </div>
                                    <div class="tutor-content">
                                        <h5 class="title">John Due</h5>
                                        <div class="rbt-review">
                                            <div class="rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <span class="rating-count"> (15 Reviews)</span>
                                        </div>
                                        <ul class="rbt-meta rbt-meta-white mt--5">
                                            <li><i class="feather-book"></i>20 Courses</li>
                                            <li><i class="feather-users"></i>40 Students</li>
                                        </ul>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- End Tutor Information  -->
                        </div>
                        <!-- End Dashboard Top  -->
                    </div>
                @endif

                <div class="col-lg-12 mt--30">
                    <div class="profile-content rbt-shadow-box">
                        <h4 class="rbt-title-style-3">Biography</h4>
                        <div class="row g-5">
                            <div class="col-lg-8">
                                {{-- <p class="mt--10 mb--20">I'm the Front-End Developer for #Rainbow IT in Bangladesh, OR. I have serious passion for UI effects, animations and creating intuitive, dynamic user experiences.</p>
                                <ul class="social-icon social-default justify-content-start">
                                    <li><a href="https://www.facebook.com/">
                                            <i class="feather-facebook"></i>
                                        </a>
                                    </li>
                                    <li><a href="https://www.twitter.com">
                                            <i class="feather-twitter"></i>
                                        </a>
                                    </li>
                                    <li><a href="https://www.instagram.com/">
                                            <i class="feather-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkdin.com/">
                                            <i class="feather-linkedin"></i>
                                        </a>
                                    </li>
                                </ul> --}}
                                {!! $teacher->detailed_info !!}
                                <ul class="rbt-information-list mt--15">
                                    <li>
                                        <a href="#"><i class="feather-phone"></i>{{ $teacher->phone_no }}</a>
                                    </li>
                                    <li>
                                        <a href="mailto:{{ $teacher->email }}"><i class="feather-mail"></i>{{ $teacher->email }}</a>
                                    </li>
                                </ul>
                            </div>
                            {{-- <div class="col-lg-2 offset-lg-2">
                                <div class="feature-sin best-seller-badge text-end h-100">
                                    <span class="rbt-badge-2 w-100 text-center badge-full-height">
                                        <span class="image"><img src="assets/images/icons/card-icon-1.png" alt="Best Seller Icon"></span> Bestseller
                                    </span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            @if(!$contents->isEmpty())
                <div class="rbt-program-area rbt-section-gapTop bg-color-white" id="program">
                    <div class="container">
                        <div class="row g-5 align-items-end mb--60">
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="section-title text-start">
                                    <h4 class="rbt-title-style-3">Contents</h4>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 col-12">
                                <div class="load-more-btn text-start text-lg-end">
                                    <a class="rbt-btn-link" href="{{ route('instructor-contents', $teacher->user_id) }}">Browse all<i class="feather-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row g-5">
                            @foreach ($contents as $key => $data)
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="">
                                        <div class="thumbnail">
                                            <a href="#">
                                                <video
                                                    id="video-{{ $data->id }}"
                                                    class="video-js vjs-default-skin"
                                                    controls
                                                    preload="auto"
                                                    width="100%"
                                                    {{-- height="360" --}}
                                                    poster="{{ $data->thumbnail }}"
                                                    data-setup='{}'>
                                                    <source src="{{ $data->file_path }}" type="video/mp4" />
                                                    Your browser does not support the video tag.
                                                </video>
                                                {{-- <img src="{!! asset('web/assets/images/gallery/gallery-03.jpg') !!}" alt="Gallery Images"> --}}
                                                <div class="rbt-bg-overlay"></div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(!$teacherCourses->isEmpty())
                <div class="rbt-profile-course-area mt--60">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="sction-title">
                                <h4 class="rbt-title-style-3">Courses</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mt--5">

                        @foreach ($teacherCourses as $key => $data)
                            <!-- Start Single Card  -->
                            <div class="col-lg-4 col-md-6 col-sm-12 col-12" data-sal-delay="150" data-sal="slide-up" data-sal-duration="800">
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
                            <!-- End Single Card  -->
                        @endforeach

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 mt--60">
                        <nav>
                            {{ $teacherCourses->withQueryString()->links('web.include.paginator') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>

@endsection
@section('scripts')
    @parent
    {{-- <script src="https://vjs.zencdn.net/8.0.4/video.min.js"></script> --}}
@endsection
