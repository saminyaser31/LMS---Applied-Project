<script>
    $(document).ready(function() {
        // When the toggle button (eye icon) is clicked
        $('.password-toggle-btn').on('click', function() {
            // Find the corresponding password input field for this button
            var passwordField = $(this).siblings('.password-input');
            var eyeIcon = $(this).find('i'); // Find the icon inside the button

            // Toggle the input type between password and text
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text'); // Show password
                eyeIcon.removeClass('ri-eye-fill').addClass('ri-eye-off-fill'); // Change to 'eye-off' icon
            } else {
                passwordField.attr('type', 'password'); // Hide password
                eyeIcon.removeClass('ri-eye-off-fill').addClass('ri-eye-fill'); // Change back to 'eye' icon
            }
        });
    });
</script>
