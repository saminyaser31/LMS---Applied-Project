<ul class="quick-access">
    {{-- <li class="access-icon rbt-mini-cart">
        <a class="rbt-cart-sidenav-activation rbt-round-btn" href="#">
            <i class="feather-shopping-cart"></i>
            <span class="rbt-cart-count">5</span>
        </a>
    </li> --}}

    <style>
        @media (min-width: 1200px) {
            #mod-cart-list {
                display: none!important;
            }
        }
    </style>

    {{-- <li class="access-icon rbt-mini-cart" id="mod-cart-list">
        <a class="rbt-round-btn" href="{{ route('cart') }}">
            <i class="feather-shopping-cart"></i>
            <span class="rbt-cart-count">{{ App\Helper\Helper::getCartCount() }}</span>
        </a>
    </li> --}}

    <li class="access-icon rbt-user-wrapper right-align-dropdown">
        <a class="rbt-round-btn" href="#">
            <i class="feather-user"></i>
        </a>
        <div class="rbt-user-menu-list-wrapper">
            <div class="inner">
                @if (isset(app('admin')->id))
                    <div class="rbt-admin-profile">
                        <div class="admin-thumbnail">
                            <img src="{!! asset('web/assets/images/team/user-avatar.png') !!}" alt="User Images">
                        </div>
                        <div class="admin-info">
                            <span class="name">{{Auth::user()->name}}</span>
                            {{-- <a class="rbt-btn-link color-primary" href="#">View Profile</a> --}}
                        </div>
                    </div>
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="feather-home"></i>
                                <span>My Dashboard</span>
                            </a>
                        </li>
                    </ul>
                    <hr class="mt--10 mb--10">
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                @elseif (isset(app('student')->id))
                    <div class="rbt-admin-profile">
                        <div class="admin-thumbnail">
                            <img src="{!! asset('web/assets/images/team/user-avatar.png') !!}" alt="User Images">
                        </div>
                        <div class="admin-info">
                            <span class="name">{{Auth::user()->name}}</span>
                            {{-- <a class="rbt-btn-link color-primary" href="#">View Profile</a> --}}
                        </div>
                    </div>
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="{{ route('student.dashboard') }}">
                                <i class="feather-home"></i>
                                <span>My Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.courses.index') }}">
                                <i class="feather-shopping-bag"></i>
                                <span>Enrolled Courses</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('wishlist') }}">
                                <i class="feather-heart"></i>
                                <span>Wishlist</span>
                            </a>
                        </li> --}}
                    </ul>
                    <hr class="mt--10 mb--10">
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="#">
                                <i class="feather-settings"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                <i class="feather-log-out"></i>
                                <span>{{ trans('global.logout') }}</span>
                            </a>
                        </li>
                    </ul>
                @elseif (isset(app('teacher')->id))
                    <div class="rbt-admin-profile">
                        <div class="admin-thumbnail">
                            <img src="{!! asset('web/assets/images/team/user-avatar.png') !!}" alt="User Images">
                        </div>
                        <div class="admin-info">
                            <span class="name">{{Auth::user()->name}}</span>
                            {{-- <a class="rbt-btn-link color-primary" href="#">View Profile</a> --}}
                        </div>
                    </div>
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="{{ route('teacher.dashboard') }}">
                                <i class="feather-home"></i>
                                <span>My Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.change-password.index') }}">
                                <i class="feather-home"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.courses.index') }}">
                                <i class="feather-home"></i>
                                <span>My Courses</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.my-students.index') }}">
                                <i class="feather-home"></i>
                                <span>My Students</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="#">
                                <i class="feather-star"></i>
                                <span>Reviews</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="feather-clock"></i>
                                <span>Order History</span>
                            </a>
                        </li> --}}
                    </ul>
                    <hr class="mt--10 mb--10">
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="#">
                                <i class="feather-settings"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                @else
                    <ul class="user-list-wrapper">
                        <li>
                            <a href="{{ route('teacher.login-page') }}"><span>Instructor</span></a>
                        </li>
                        <li>
                            <a href="{{ route('student.login-page') }}"><span>Student</span></a>
                        </li>
                    </ul>
                @endif

                <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

            </div>
        </div>
    </li>
</ul>
