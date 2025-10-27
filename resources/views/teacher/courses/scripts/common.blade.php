<script>
    $(document).ready(function() {
        // Repopulate course contents with old values
        var oldContentNo = @json($oldContentNo);
        var oldContentTitles = @json($oldContentTitles);
        var oldContentDescriptions = @json($oldContentDescriptions);

        if (oldContentTitles.length > 0) {
            for (var i = 0; i < oldContentTitles.length; i++) {
                if (i == 0) {
                    const firstCard = document.getElementById('content-card-template');
                    const firstNoInput = firstCard.querySelector('input[type="number"]');
                    const firstTitleInput = firstCard.querySelector('input[type="text"]');
                    const firstDescriptionTextarea = firstCard.querySelector('textarea');
                    // Set the values for the first card
                    firstNoInput.value = oldContentNo[0];
                    firstTitleInput.value = oldContentTitles[0];
                    firstDescriptionTextarea.value = oldContentDescriptions[0];
                } else {
                    addCard(oldContentNo[i], oldContentTitles[i], oldContentDescriptions[i]);
                }
            }
        }

        // Event delegation for add buttons
        courseContentSection.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-btn')) {
                addCard();
            }
        });

        // Event delegation for delete buttons
        courseContentSection.addEventListener('click', function(e) {
            if (e.target.classList.contains('delete-btn')) {
                e.target.closest('.card').remove();
            }
        });
    });

    let cardCount = 0;
    const addButtons = document.querySelector('.add-btn');
    const courseContentSection = document.getElementById('course-content-section');

    // Function to add a new card
    function addCard(contentNo = '', contentTitle = '', contentDescription = '') {
        cardCount++; // Increment the counter for unique IDs

        // Clone the content card
        const newCard = document.getElementById('content-card-template').cloneNode(true);
        // Remove the ID from the cloned card
        newCard.removeAttribute('id');
        // Show the delete button
        newCard.querySelector('.delete-btn').style.display = 'inline-block';

        // Update the IDs of inputs and textareas
        const noInput = newCard.querySelector('input[type="number"]');
        const titleInput = newCard.querySelector('input[type="text"]');
        const descriptionTextarea = newCard.querySelector('textarea');

        noInput.id = `content_no_${cardCount}`;
        titleInput.id = `content_title_${cardCount}`;
        descriptionTextarea.id = `content_description_${cardCount}`;
        var textareaId = `content_description_${cardCount}`;

        // Clear/Set input values
        noInput.value = contentNo;
        titleInput.value = contentTitle;
        descriptionTextarea.value = contentDescription;

        // Append the new card to the section
        courseContentSection.appendChild(newCard);
        // initializeCkEditor5(textareaId);
    }
</script>
