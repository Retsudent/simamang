/**
 * SIMAMANG Notification System
 * Handles all notifications with 3-second auto-hide functionality
 */

class NotificationSystem {
    constructor() {
        this.toastContainer = null;
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        this.toastContainer = document.getElementById('toastContainer');
        if (!this.toastContainer) {
            this.toastContainer = document.createElement('div');
            this.toastContainer.id = 'toastContainer';
            this.toastContainer.className = 'position-fixed top-0 end-0 p-3';
            this.toastContainer.style.zIndex = '2001';
            document.body.appendChild(this.toastContainer);
        }

        // Auto-hide existing alerts on page load
        this.autoHideExistingAlerts();
        
        // Handle flashdata notifications
        this.handleFlashdata();
    }

    /**
     * Show a toast notification
     * @param {string} message - The message to display
     * @param {string} type - The type of notification (success, error, warning, info)
     * @param {number} duration - Duration in milliseconds (default: 3000)
     */
    showToast(message, type = 'success', duration = 3000) {
        const id = 'toast-' + Date.now();
        const icon = this.getIconForType(type);
        const bg = this.getBackgroundForType(type);
        const text = type === 'warning' ? 'text-dark' : 'text-white';
        
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center ${bg} ${text} border-0 show mb-2`;
        toastEl.id = id;
        toastEl.setAttribute('role', 'alert');
        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        this.toastContainer.appendChild(toastEl);
        
        // Auto-hide after specified duration
        this.autoHideElement(toastEl, duration);
        
        return toastEl;
    }

    /**
     * Show an alert notification in the content area
     * @param {string} message - The message to display
     * @param {string} type - The type of notification (success, error, warning, info)
     * @param {number} duration - Duration in milliseconds (default: 3000)
     */
    showAlert(message, type = 'success', duration = 3000) {
        const alertContainer = document.querySelector('.content-wrapper');
        if (!alertContainer) return null;

        const icon = this.getIconForType(type);
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        
        alertDiv.innerHTML = `
            <i class="bi bi-${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert at the top of content wrapper
        alertContainer.insertBefore(alertDiv, alertContainer.firstChild);

        // Auto-hide after specified duration
        this.autoHideElement(alertDiv, duration);
        
        return alertDiv;
    }

    /**
     * Auto-hide an element with smooth animation
     * @param {HTMLElement} element - The element to hide
     * @param {number} duration - Duration before hiding
     */
    autoHideElement(element, duration = 3000) {
        setTimeout(() => {
            if (element && element.parentNode) {
                element.classList.add('fade');
                setTimeout(() => {
                    if (element && element.parentNode) {
                        element.classList.add('fade-out');
                        setTimeout(() => {
                            if (element && element.parentNode) {
                                element.remove();
                            }
                        }, 300);
                    }
                }, 300);
            }
        }, duration);
    }

    /**
     * Auto-hide all existing alerts on page load
     */
    autoHideExistingAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            this.autoHideElement(alert, 3000);
        });
    }

    /**
     * Handle flashdata notifications from server
     */
    handleFlashdata() {
        // Get flashdata from PHP variables (these should be set in the layout)
        const flashSuccess = window.flashSuccess || null;
        const flashError = window.flashError || null;
        
        if (flashSuccess) {
            this.showToast(flashSuccess, 'success');
        }
        
        if (flashError) {
            this.showToast(flashError, 'danger');
        }
    }

    /**
     * Get icon for notification type
     * @param {string} type - The notification type
     * @returns {string} The icon name
     */
    getIconForType(type) {
        const icons = {
            'success': 'check-circle',
            'error': 'x-circle',
            'danger': 'x-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * Get background class for notification type
     * @param {string} type - The notification type
     * @returns {string} The background class
     */
    getBackgroundForType(type) {
        const backgrounds = {
            'success': 'bg-success',
            'error': 'bg-danger',
            'danger': 'bg-danger',
            'warning': 'bg-warning',
            'info': 'bg-info'
        };
        return backgrounds[type] || 'bg-info';
    }

    /**
     * Show success notification
     * @param {string} message - The success message
     */
    success(message) {
        return this.showToast(message, 'success');
    }

    /**
     * Show error notification
     * @param {string} message - The error message
     */
    error(message) {
        return this.showToast(message, 'error');
    }

    /**
     * Show warning notification
     * @param {string} message - The warning message
     */
    warning(message) {
        return this.showToast(message, 'warning');
    }

    /**
     * Show info notification
     * @param {string} message - The info message
     */
    info(message) {
        return this.showToast(message, 'info');
    }
}

// Initialize notification system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Create global notification instance
    window.notifications = new NotificationSystem();
    
    // Create global helper functions for backward compatibility
    window.showToast = function(message, type = 'success') {
        return window.notifications.showToast(message, type);
    };
    
    window.showNotification = function(message, type = 'success', duration = 3000) {
        return window.notifications.showAlert(message, type, duration);
    };
    
    // Enhanced Bootstrap alert auto-dismiss
    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            try {
                // Use Bootstrap's built-in close method if available
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                if (bsAlert) {
                    bsAlert.close();
                } else {
                    // Fallback: manual removal with animation
                    window.notifications.autoHideElement(el, 0);
                }
            } catch (e) {
                // Fallback: manual removal with animation
                window.notifications.autoHideElement(el, 0);
            }
        });
    }, 3000);
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationSystem;
}

