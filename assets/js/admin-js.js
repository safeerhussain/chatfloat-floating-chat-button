document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input, select');
    
    // Highlight changed inputs with a glow effect
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            input.classList.add('highlight-change');
            setTimeout(() => {
                input.classList.remove('highlight-change');
            }, 1000); // Glow effect duration
        });
    });
});
