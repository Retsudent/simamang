// Dark Mode Toggle for SIMAMANG
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dark mode from localStorage
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'dark') {
        document.body.classList.add('dark');
        updateToggleIcon(true);
    }
    
    // Get dark mode toggle button
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        // Add click event listener
        darkModeToggle.addEventListener('click', function() {
            // Toggle dark mode
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            
            // Save preference to localStorage
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            // Update toggle icon
            updateToggleIcon(isDark);
        });
        
        // Add hover effects
        darkModeToggle.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        darkModeToggle.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }
    
    // Function to update toggle icon
    function updateToggleIcon(isDark) {
        const darkIcon = document.querySelector('.dark-icon');
        const lightIcon = document.querySelector('.light-icon');
        
        if (darkIcon && lightIcon) {
            if (isDark) {
                // Dark mode active - show sun icon
                darkIcon.style.opacity = '0';
                darkIcon.style.transform = 'scale(0.8) rotate(-90deg)';
                lightIcon.style.opacity = '1';
                lightIcon.style.transform = 'scale(1) rotate(0deg)';
            } else {
                // Light mode active - show moon icon
                darkIcon.style.opacity = '1';
                darkIcon.style.transform = 'scale(1) rotate(0deg)';
                lightIcon.style.opacity = '0';
                lightIcon.style.transform = 'scale(0.8) rotate(90deg)';
            }
        }
    }
    

    
    // Apply dark mode styles to specific elements
    function applyDarkModeStyles() {
        if (document.body.classList.contains('dark')) {
            // Add dark mode classes to specific components
            document.querySelectorAll('.card').forEach(card => {
                card.classList.add('dark-mode-card');
            });
            
            document.querySelectorAll('.table').forEach(table => {
                table.classList.add('dark-mode-table');
            });
            
            document.querySelectorAll('.form-control').forEach(input => {
                input.classList.add('dark-mode-input');
            });
        } else {
            // Remove dark mode classes
            document.querySelectorAll('.dark-mode-card').forEach(card => {
                card.classList.remove('dark-mode-card');
            });
            
            document.querySelectorAll('.dark-mode-table').forEach(table => {
                table.classList.remove('dark-mode-table');
            });
            
            document.querySelectorAll('.dark-mode-input').forEach(input => {
                input.classList.remove('dark-mode-input');
            });
        }
    }
    
    // Watch for theme changes and apply styles
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                applyDarkModeStyles();
            }
        });
    });
    
    // Start observing
    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // Initial application of styles
    applyDarkModeStyles();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initDarkMode: true };
}

