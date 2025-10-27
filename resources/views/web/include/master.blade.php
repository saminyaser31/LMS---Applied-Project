<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Google Tag Manager -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-5BN46DR7');
        </script>
        <!-- End Google Tag Manager -->

        @include('web.include.title-meta')

        @include('web.include.head-css')

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    	<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

        @yield('css')

        <!--Jquery-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </head>

    <body class="rbt-header-sticky">
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5BN46DR7" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-M44QQNE7Y4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-M44QQNE7Y4');
        </script>

        @include('web.include.color')
        {{-- @include('web.include.campaign') --}}
        @include('web.include.header')
        @include('web.include.mobile-menu')
        @include('web.include.side-cart')

        @yield('content')

        @yield('scripts')

        @include('web.include.seperator')
        @include('web.include.copyright')
        @include('web.include.progress-bar')
        @include('web.include.vendor-scripts')
    </body>

</html>
