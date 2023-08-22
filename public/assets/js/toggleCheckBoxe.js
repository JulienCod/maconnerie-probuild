const toggleCheckboxes = document.querySelectorAll('.toggle-checkbox');

    toggleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Soumettre le formulaire parent du checkbox
            const form = this.closest('form');
            form.submit();
        });
    });