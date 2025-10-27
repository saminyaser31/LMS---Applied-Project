<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        @include('layouts.common.logo')


        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>

    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('student/profile*') ? 'active' : '' }} {{ request()->is('student/change-password*') ? 'collapsed active' : '' }}" href="#studentProfilePages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="studentProfilePages">
                        <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Profile</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is('student/profile*') ? 'show' : '' }} {{ request()->is('student/change-password*') ? 'show' : '' }}" id="studentProfilePages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('student.profile.index') }}" class="nav-link {{ request()->is('student/profile') || request()->is('student/profile/*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account"></i> <span >My Profile</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('student.change-password.index') }}" class="nav-link {{ request()->is('student/change-password') || request()->is('student/change-password/*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account"></i> <span >Change Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('student.orders.index') }}" class="nav-link {{ request()->is('student/orders') || request()->is('student/orders/*') ? 'active' : '' }}">
                        <i class="mdi mdi-book-open"></i> <span >My Orders</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('student.courses.index') }}" class="nav-link {{ request()->is('student/courses') || request()->is('student/courses/*') ? 'active' : '' }}">
                        <i class="mdi mdi-book-open"></i> <span >My Courses</span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('student.reviews.index') }}" class="nav-link {{ request()->is('student/reviews') || request()->is('student/reviews/*') ? 'active' : '' }}">
                        <i class="mdi mdi-star"></i> <span >Reviews</span>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <i class="mdi mdi-logout"></i> <span >{{ trans('global.logout') }}</span>
                    </a>
                </li>

                <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
