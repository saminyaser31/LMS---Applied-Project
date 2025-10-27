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
                    <a class="nav-link menu-link {{ request()->is('teacher/profile*','teacher/change-password*') ? 'collapsed active' : '' }}" href="#teacherProfilePages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="teacherProfilePages">
                        <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Profile</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is('teacher/profile*','teacher/change-password*') ? 'show' : '' }}" id="teacherProfilePages">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('teacher.profile.index') }}" class="nav-link {{ request()->is('teacher/profile','teacher/profile/*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account"></i> <span >My Profile</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('teacher.change-password.index') }}" class="nav-link {{ request()->is('teacher/change-password','teacher/change-password/*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account"></i> <span >Change Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                @if(Auth::user()->approved == App\Models\User::STATUS_APPROVED)
                    {{-- <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('teacher/courses*','teacher/course-contents*','teacher.course-materials*') ? 'collapsed active' : '' }}" href="#manageCoursesPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manageCoursesPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Courses</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('teacher/courses*','teacher/course-contents*','teacher/course-materials*') ? 'show' : '' }}" id="manageCoursesPages">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('teacher.courses.index') }}" class="nav-link {{ request()->is('teacher/courses','teacher/courses/*') ? 'active' : '' }}">
                                        <i class="mdi mdi-book-open"></i> <span >My Courses</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('teacher.course-contents.index') }}" class="nav-link {{ request()->is('teacher/course-contents','teacher/course-contents/*') ? 'active' : '' }}">
                                        <i class="mdi mdi-book-open"></i> <span >Course Contents</span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('teacher.course-materials.index') }}" class="nav-link {{ request()->is('teacher/course-materials','teacher/course-materials/*') ? 'active' : '' }}">
                                        <i class="mdi mdi-book-open"></i> <span >Course Materials</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.my-students.index') }}" class="nav-link {{ request()->is('teacher/my-students') || request()->is('teacher/my-students/*') ? 'active' : '' }}">
                            <i class="mdi mdi-star"></i> <span >My Students</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.my-contents.index') }}" class="nav-link {{ request()->is('teacher/my-contents') || request()->is('teacher/my-contents/*') ? 'active' : '' }}">
                            <i class="mdi mdi-star"></i> <span >My Contents</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.reviews.index') }}" class="nav-link {{ request()->is('teacher/reviews') || request()->is('teacher/reviews/*') ? 'active' : '' }}">
                            <i class="mdi mdi-star"></i> <span >Reviews</span>
                        </a>
                    </li> --}}
                @endif

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
