<!-- 'classic' color theme -->
<link rel="stylesheet" href="{!! asset('theme/admin/assets/libs/@simonwep/pickr/themes/classic.min.css') !!}" />

<!-- Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" id="theme-styles">

{{-- Bootstrap Color Pickr --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">

<!--datatable css-->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

<!--datatable responsive css-->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">


<!-- Layout config Js -->
<script src="{!! asset('theme/admin/assets/js/layout.js') !!}"></script>
<!-- Bootstrap Css -->
<link href="{!! asset('theme/admin/assets/css/bootstrap.min.css') !!}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{!! asset('theme/admin/assets/css/icons.min.css') !!}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{!! asset('theme/admin/assets/css/app.min.css') !!}" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="{!! asset('theme/admin/assets/css/custom.min.css') !!}" id="app-style" rel="stylesheet" type="text/css" />

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- ckeditor css -->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.3.0/ckeditor5.css" />

<!--Jquery-->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<style>
    .select2-container .select2-selection--multiple .select2-selection__choice {
        background-color: #4b38b3;
        color: #fff;
        border-radius: 3px;
        margin-top: 6px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        padding: 4px;
        padding-left: 20px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff;
        padding: 4px;
    }

    /* Web Styles Starts */
    /* div.rbt-banner-area.rbt-banner-1 {
        background: linear-gradient(to right, #8a2be2, #9370db) !important;
    } */
    /* Web Styles Starts */

    /* Admin Dashboard Styles Starts */
    label.required::after {
        content: " *";
        color: red;
    }

    [data-sidebar=gradient-2] .navbar-menu, [data-sidebar=gradient-3] .navbar-menu, [data-sidebar=gradient-4] .navbar-menu, [data-sidebar=gradient] .navbar-menu {
        /* background: linear-gradient(to right, #b38c38, #cb6b45); */
        background: linear-gradient(to right, #8a2be2, #9370db);
    }

    [data-sidebar=gradient-2] .navbar-nav .nav-link, [data-sidebar=gradient-3] .navbar-nav .nav-link, [data-sidebar=gradient-4] .navbar-nav .nav-link, [data-sidebar=gradient] .navbar-nav .nav-link {
        color: rgb(255 255 255 / 83%);
    }

    [data-sidebar=gradient-2][data-sidebar-size=sm] .navbar-brand-box, [data-sidebar=gradient-3][data-sidebar-size=sm] .navbar-brand-box, [data-sidebar=gradient-4][data-sidebar-size=sm] .navbar-brand-box, [data-sidebar=gradient][data-sidebar-size=sm] .navbar-brand-box {
        background: linear-gradient(to right, #8a2be2, #9370db);
    }

    [data-sidebar=gradient-2] .navbar-nav .nav-sm .nav-link, [data-sidebar=gradient-3] .navbar-nav .nav-sm .nav-link, [data-sidebar=gradient-4] .navbar-nav .nav-sm .nav-link, [data-sidebar=gradient] .navbar-nav .nav-sm .nav-link {
        color: rgb(255 255 255 / 83%);
    }
    /* Admin Dashboad Styles Ends */
</style>
