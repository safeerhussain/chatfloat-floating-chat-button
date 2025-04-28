document.addEventListener('DOMContentLoaded', function () {
    // Get all the form input fields
    const inputs = document.querySelectorAll('input, select');
    
    // Add event listener to inputs for change
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            input.classList.add('highlight-change');
            setTimeout(() => {
                input.classList.remove('highlight-change');
            }, 1000); // Duration for the glow effect
        });
    });
});
