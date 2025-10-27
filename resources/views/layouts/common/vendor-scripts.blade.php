<!-- JAVASCRIPT -->
<script src="{!! asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js') !!}"></script>
<script src="{!! asset('theme/admin/assets/libs/simplebar/simplebar.min.js') !!}"></script>
<script src="{!! asset('theme/admin/assets/libs/node-waves/waves.min.js') !!}"></script>
<script src="{!! asset('theme/admin/assets/libs/feather-icons/feather.min.js') !!}"></script>
<script src="{!! asset('theme/admin/assets/js/pages/plugins/lord-icon-2.1.0.js') !!}"></script>
<script src="{!! asset('theme/admin/assets/js/plugins.js') !!}"></script>

<!--Jquery-->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="{!! asset('theme/admin/assets/js/pages/datatables.init.js') !!}"></script>

<!--select2 cdn-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{!! asset('theme/admin/assets/js/pages/select2.init.js') !!}"></script>

<!-- App js -->
<script src="{!! asset('theme/admin/assets/js/app.js') !!}"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".datetimepicker", {
        enableTime: true,
        // dateFormat: "Y-m-d H:i",
        noCalendar: false,
        dateFormat: "Y-m-d H:i:S", // Change to include seconds
        time_24hr: true, // Optional: to use 24-hour time format
        enableSeconds: true // Enable seconds in the picker
    });
</script>

{{-- Bootstrap Color Pickr --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.3.3/js/bootstrap-colorpicker.min.js"></script>
