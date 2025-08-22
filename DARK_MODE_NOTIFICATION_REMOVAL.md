# Dark Mode Notification Removal

## Problem
User reported that switching between dark and light mode was showing unnecessary notifications/toasts.

## Solution
Removed the notification system from dark mode toggle functionality.

## Changes Made

### File: `public/assets/js/dark-mode.js`

**Removed:**
- Function call to `showThemeFeedback(isDark)` in the click event listener
- Entire `showThemeFeedback()` function that was creating toast notifications

**Before:**
```javascript
darkModeToggle.addEventListener('click', function() {
    // Toggle dark mode
    document.body.classList.toggle('dark');
    const isDark = document.body.classList.contains('dark');
    
    // Save preference to localStorage
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Update toggle icon
    updateToggleIcon(isDark);
    
    // Optional: Show feedback
    showThemeFeedback(isDark); // REMOVED
});

// Function to show theme feedback
function showThemeFeedback(isDark) { // REMOVED
    const message = isDark ? 'Dark mode diaktifkan' : 'Light mode diaktifkan';
    const type = 'success';
    
    // Create toast notification
    if (typeof showToast === 'function') {
        showToast(message, type);
    } else {
        // Fallback: simple alert
        console.log(message);
    }
}
```

**After:**
```javascript
darkModeToggle.addEventListener('click', function() {
    // Toggle dark mode
    document.body.classList.toggle('dark');
    const isDark = document.body.classList.contains('dark');
    
    // Save preference to localStorage
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
    
    // Update toggle icon
    updateToggleIcon(isDark);
});
```

## Benefits
- **Cleaner UX**: No unnecessary notifications when switching themes
- **Faster Response**: Immediate theme switching without notification delay
- **Less Visual Clutter**: Reduces toast notifications in the interface
- **Better Performance**: Eliminates unnecessary function calls

## Result
Now when users click the dark mode toggle button, the theme will switch immediately without showing any notification toast. The change is still saved to localStorage and the toggle icon updates correctly, but without the visual feedback notification.

