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
                @can('user_management_access')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'collapsed active' : '' }} {{ request()->is('admin/users*') ? 'collapsed active' : '' }} {{ request()->is('admin/audit-logs*') ? 'collapsed active' : '' }}" href="#userManagementPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="userManagementPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">{{ trans('cruds.userManagement.title') }}</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/permissions*') ? 'show' : '' }} {{ request()->is('admin/roles*') ? 'show' : '' }} {{ request()->is('admin/users*') ? 'show' : '' }} {{ request()->is('admin/audit-logs*') ? 'show' : '' }}" id="userManagementPages">
                            <ul class="nav nav-sm flex-column">
                                @can('permission_access')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                            <span >{{ trans('cruds.permission.title') }}</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('role_access')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                            <span >{{ trans('cruds.role.title') }}</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('user_access')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                            <span >{{ trans('cruds.user.title') }}</span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- @can('audit_log_access')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.audit-logs.index') }}" class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                            <span >{{ trans('cruds.auditLog.title') }}</span>
                                        </a>
                                    </li>
                                @endcan --}}
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('teacher_management_access')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('admin/courses*','admin/teachers*','teacher/courses*') ? 'collapsed active' : '' }}" href="#teacherManagementPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="teacherManagementPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Teacher Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/courses*','admin/teachers*','teacher/courses*') ? 'show' : '' }}" id="teacherManagementPages">
                            <ul class="nav nav-sm flex-column">
                                @can('access_teacher')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.teachers.index') }}" class="nav-link {{ request()->is('admin/teachers','admin/teachers/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-account"></i> <span >Manage Teacher</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('access_new_teacher_requests')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.teachers.approve-requests.index') }}" class="nav-link {{ request()->is('admin/teachers/approve-requests','admin/teachers/approve-requests/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-account"></i> <span >New Teacher Requests</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

                {{-- @can('access_course_management')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('admin/courses*','admin/course-contents*','admin.course-materials*','admin/course-category*','admin/course-subject*','admin/course-level*') ? 'collapsed active' : '' }}" href="#manageCoursesPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="manageCoursesPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Courses</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/courses*','admin/course-contents*','admin/course-materials*','admin/course-category*','admin/course-subject*','admin/course-level*') ? 'show' : '' }}" id="manageCoursesPages">
                            <ul class="nav nav-sm flex-column">
                                @can('view_course')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->is('admin/courses','admin/courses/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >My Courses</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_course_content')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.course-contents.index') }}" class="nav-link {{ request()->is('admin/course-contents','admin/course-contents/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >Course Contents</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_course_material')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.course-materials.index') }}" class="nav-link {{ request()->is('admin/course-materials','admin/course-materials/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >Course Materials</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_course_category')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.course-category.index') }}" class="nav-link {{ request()->is('admin/course-category','admin/course-category/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >Course Category</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_course_subject')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.course-subject.index') }}" class="nav-link {{ request()->is('admin/course-subject','admin/course-subject/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >Course Subject</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('view_course_level')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.course-level.index') }}" class="nav-link {{ request()->is('admin/course-level','admin/course-level/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open"></i> <span >Course Level</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan --}}

                {{-- @can('access_order_management')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('admin/orders*') ? 'collapsed active' : '' }}" href="#orderManagementPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="orderManagementPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Order Management</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/orders*') ? 'show' : '' }}" id="orderManagementPages">
                            <ul class="nav nav-sm flex-column">
                                @can('access_teacher')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->is('admin/orders','admin/orders/*') ? 'active' : '' }}">
                                            <i class="mdi mdi-account"></i> <span >Manage Order</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan --}}

                {{-- @can('access_web_elements')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->is('admin/web/image*', 'admin/web/color*', 'admin/web/typography*', 'admin/web/meta-contents*') ? 'collapsed active' : '' }}" href="#webElementManagementPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="webElementManagementPages">
                            <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Web Elements</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->is('admin/web/image*', 'admin/web/color*', 'admin/web/typography*', 'admin/web/meta-contents*') ? 'show' : '' }}" id="webElementManagementPages">
                            <ul class="nav nav-sm flex-column">
                                @can('access_logo')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.web.image') }}" class="nav-link {{ request()->is('admin/web/image') || request()->is('admin/web/image/*') ? 'active' : '' }}">
                                            <span >Images</span>
                                        </a>
                                    </li>
                                @endcan

                                @can('access_color')
                                    <li class="nav-item">
                                        <a href="{{ route('admin.web.color') }}" class="nav-link {{ request()->is('admin/web/color') || request()->is('admin/web/color/*') ? 'active' : '' }}">
                                            <span >Color</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan --}}

                {{-- <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is('admin/home/*', 'admin/about-us/*', 'admin/contact-us/*', 'admin/privacy-policy*', 'admin/faqs*') ? 'collapsed active' : '' }}" href="#webManagementPages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="webManagementPages">
                        <i class="mdi mdi-sticker-text-outline"></i> <span data-key="t-pages">Manage Web pages</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is('admin/home/*', 'admin/about-us/*', 'admin/contact-us/*', 'admin/faqs*', 'admin/privacy-policy*') ? 'show' : '' }}" id="webManagementPages">
                        <ul class="nav nav-sm flex-column">
                            @can('access_home')
                                <li class="nav-item">
                                    <a href="#sidebarHome" class="nav-link {{ request()->is('admin/home*') ? 'collapsed active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHome">
                                        Home
                                    </a>
                                    <div class="collapse menu-dropdown {{ request()->is('admin/home*') ? 'show' : '' }}" id="sidebarHome">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.home.hero-section') }}" class="nav-link {{ request()->is('admin/home/hero-section', 'admin/home/hero-section/*') ? 'active' : '' }}">Hero Section</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.home.campaign-section') }}" class="nav-link {{ request()->is('admin/home/campaign-section-', 'admin/home/campaign-section/*') ? 'active' : '' }}">Campaign Section</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endcan

                            @can('access_about_us')
                                <li class="nav-item">
                                    <a href="#sidebarAboutUs" class="nav-link {{ request()->is('admin/about-us*') ? 'collapsed active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAboutUs">
                                        About Us
                                    </a>
                                    <div class="collapse menu-dropdown {{ request()->is('admin/about-us*') ? 'show' : '' }}" id="sidebarAboutUs">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.about-us.section-1') }}" class="nav-link {{ request()->is('admin/about-us/section-1', 'admin/about-us/section-1/*') ? 'active' : '' }}">Section One</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.about-us.section-2') }}" class="nav-link {{ request()->is('admin/about-us/section-2', 'admin/about-us/section-2/*') ? 'active' : '' }}">Section Two</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.about-us.section-3') }}" class="nav-link {{ request()->is('admin/about-us/section-3', 'admin/about-us/section-3/*') ? 'active' : '' }}">Section Three</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endcan

                            @can('access_contact_us')
                                <li class="nav-item">
                                    <a href="#sidebarContactUs" class="nav-link {{ request()->is('admin/contact-us/*') ? 'collapsed active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarContactUs">
                                        Contact Us
                                    </a>
                                    <div class="collapse menu-dropdown {{ request()->is('admin/contact-us/*') ? 'show' : '' }}" id="sidebarContactUs">
                                        <ul class="nav nav-sm flex-column">
                                            <li class="nav-item">
                                                <a href="{{ route('admin.contact-us.edit') }}" class="nav-link {{ request()->is('admin/contact-us/edit') || request()->is('admin/contact-us/edit/*') ? 'active' : '' }}">Section Contents</a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ route('admin.contact-us.index') }}" class="nav-link {{ request()->is('admin/contact-us/index') || request()->is('admin/contact-us/index/*') ? 'active' : '' }}">Messages</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            @endcan

                            @can('access_faq')
                                <li class="nav-item">
                                    <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->is('admin/faqs') || request()->is('admin/faqs/*') ? 'active' : '' }}">
                                        <span >FAQ</span>
                                    </a>
                                </li>
                            @endcan

                            @can('access_privacy_policy')
                                <li class="nav-item">
                                    <a href="{{ route('admin.privacy-policy.index') }}" class="nav-link {{ request()->is('admin/privacy-policy') || request()->is('admin/privacy-policy/*') ? 'active' : '' }}">
                                        <span >Privacy & Policy</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
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
