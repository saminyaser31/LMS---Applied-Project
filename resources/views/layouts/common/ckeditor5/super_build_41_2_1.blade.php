<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/super-build/ckeditor.js"></script>
<script src="{{ URL::asset('theme/admin/assets/js/ckeditor5-init.js') }}"></script>

<script>
    document.querySelectorAll('textarea.ckeditor-classic').forEach(textarea => {
        // Get the ID of each textarea
        const textarea_id = textarea.getAttribute('id');
        // console.log(textarea_id);
        // Call ckEditor_Generator function with textarea_id
        ckEditor_Generator(textarea_id);
    });
</script>
