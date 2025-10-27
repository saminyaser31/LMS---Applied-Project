<!DOCTYPE html>
<html data-layout="vertical" data-topbar="light" data-sidebar="gradient" data-sidebar-size="lg">

    <head>

        <title>Dashboard</title>
        @include('layouts.common.title-meta')


        <!-- jsvectormap css -->
        <link href="{!! asset('theme/admin/assets/libs/jsvectormap/css/jsvectormap.min.css') !!}" rel="stylesheet" type="text/css" />

        <!--Swiper slider css-->
        <link href="{!! asset('theme/admin/assets/libs/swiper/swiper-bundle.min.css') !!}" rel="stylesheet" type="text/css" />

        @include('layouts.common.head-css')

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

        @yield('css')

        <!--Jquery-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>

    <body>

        <!-- Begin page -->
        <div id="layout-wrapper">

            @include('layouts.common.topbar')
            @include('layouts.common.navbar')

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">


                @yield('content')


                @include('layouts.common.footer')

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        @yield('scripts')

        @include('layouts.common.customizer')
        @include('layouts.common.vendor-scripts')


    </body>

</html>
