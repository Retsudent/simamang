// Search Enhancement for SIMAMANG

document.addEventListener('DOMContentLoaded', function() {
    // Enhance all search forms
    enhanceSearchForms();
});

function enhanceSearchForms() {
    // Find all search input groups
    const searchInputs = document.querySelectorAll('.input-group input[type="search"], .input-group input[name="q"], .input-group input[name="search"], .input-group input[name="qs"]');
    
    searchInputs.forEach(input => {
        const inputGroup = input.closest('.input-group');
        const searchButton = inputGroup.querySelector('.btn');
        
        // Add loading state to search button
        inputGroup.addEventListener('submit', function(e) {
            if (input.value.trim() !== '') {
                searchButton.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                searchButton.disabled = true;
                searchButton.classList.add('btn-loading');
            }
        });
        
        // Add enter key support
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = input.closest('form');
                if (form) {
                    form.submit();
                }
            }
        });
        
        // Add clear button functionality
        if (input.value.trim() !== '') {
            addClearButton(inputGroup, input);
        }
        
        // Listen for input changes to add/remove clear button
        input.addEventListener('input', function() {
            if (input.value.trim() !== '') {
                if (!inputGroup.querySelector('.btn-clear')) {
                    addClearButton(inputGroup, input);
                }
            } else {
                const clearBtn = inputGroup.querySelector('.btn-clear');
                if (clearBtn) {
                    clearBtn.remove();
                }
            }
        });
    });
}

function addClearButton(inputGroup, input) {
    // Remove existing clear button if any
    const existingClearBtn = inputGroup.querySelector('.btn-clear');
    if (existingClearBtn) {
        existingClearBtn.remove();
    }
    
    // Create clear button
    const clearBtn = document.createElement('button');
    clearBtn.type = 'button';
    clearBtn.className = 'btn btn-outline-secondary btn-clear';
    clearBtn.innerHTML = '<i class="bi bi-x"></i>';
    clearBtn.title = 'Clear search';
    
    // Insert clear button before search button
    const searchButton = inputGroup.querySelector('.btn');
    inputGroup.insertBefore(clearBtn, searchButton);
    
    // Add clear functionality
    clearBtn.addEventListener('click', function() {
        input.value = '';
        input.focus();
        clearBtn.remove();
        
        // Submit form to clear results
        const form = input.closest('form');
        if (form) {
            form.submit();
        }
    });
}

// Add CSS for search enhancements
const searchStyles = `
    .btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-clear {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: 0;
    }
    
    .btn-clear + .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: 0;
    }
    
    /* Light mode clear button */
    body:not(.dark) .input-group .btn-clear {
        color: #6c757d;
        border-color: #dee2e6;
        background-color: #fff;
    }
    
    body:not(.dark) .input-group .btn-clear:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Dark mode clear button */
    body.dark .input-group .btn-clear {
        color: #adb5bd;
        border-color: #495057;
        background-color: #212529;
    }
    
    body.dark .input-group .btn-clear:hover {
        color: #fff;
        background-color: #adb5bd;
        border-color: #adb5bd;
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = searchStyles;
document.head.appendChild(styleSheet);
