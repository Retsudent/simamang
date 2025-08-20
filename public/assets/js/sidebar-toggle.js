// Sidebar Toggle for SIMAMANG - Works on all devices
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    
    // Check if elements exist
    if (!sidebarToggle || !sidebar || !mainContent) {
        console.warn('Sidebar elements not found');
        return;
    }
    
    // Initialize sidebar state
    function initializeSidebar() {
        const isMobile = window.innerWidth <= 768;
        const savedState = localStorage.getItem('sidebarCollapsed');
        
        if (isMobile) {
            // Mobile: always start collapsed
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            sidebarToggle.classList.add('active');
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            // Desktop: check localStorage
            if (savedState === 'true') {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                sidebarToggle.classList.add('active');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
                sidebarToggle.classList.remove('active');
            }
        }
    }
    
    // Toggle sidebar function
    function toggleSidebar() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            // Mobile behavior
            const isOpen = sidebar.classList.contains('mobile-open');
            
            if (isOpen) {
                // Close sidebar
                sidebar.classList.remove('mobile-open');
                sidebar.classList.add('collapsed');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
                sidebarToggle.classList.add('active');
            } else {
                // Open sidebar
                sidebar.classList.add('mobile-open');
                sidebar.classList.remove('collapsed');
                if (mobileOverlay) mobileOverlay.classList.add('active');
                sidebarToggle.classList.remove('active');
            }
        } else {
            // Desktop behavior
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            sidebarToggle.classList.toggle('active');
        }
        
        // Save state to localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        
        // Debug log
        console.log('Sidebar toggled:', {
            isMobile: isMobile,
            isCollapsed: isCollapsed,
            classes: sidebar.className
        });
    }
    
    // Close sidebar function
    function closeSidebar() {
        const isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            sidebar.classList.remove('mobile-open');
            sidebar.classList.add('collapsed');
            if (mobileOverlay) mobileOverlay.classList.remove('active');
            sidebarToggle.classList.add('active');
        } else {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            sidebarToggle.classList.add('active');
        }
        
        localStorage.setItem('sidebarCollapsed', 'true');
    }
    
    // Event listeners
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Sidebar toggle clicked');
            toggleSidebar();
        });
    }
    
    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                if (sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                }
            }
        }
    });
    
    // Close sidebar when clicking overlay
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    }
    
    // Close sidebar when clicking close button
    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                closeSidebar();
            }
        });
    }
    
    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            console.log('Window resized to:', window.innerWidth);
            initializeSidebar();
        }, 250);
    });
    
    // Handle escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-open')) {
                closeSidebar();
            }
        }
    });
    
    // Touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left - close sidebar
                if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-open')) {
                    closeSidebar();
                }
            } else {
                // Swipe right - open sidebar
                if (window.innerWidth <= 768 && sidebar.classList.contains('collapsed')) {
                    toggleSidebar();
                }
            }
        }
    }
    
    // Initialize sidebar on page load
    initializeSidebar();
    
    // Debug info
    console.log('Sidebar toggle initialized', {
        sidebarToggle: !!sidebarToggle,
        sidebar: !!sidebar,
        mainContent: !!mainContent,
        mobileOverlay: !!mobileOverlay,
        mobileCloseBtn: !!mobileCloseBtn,
        windowWidth: window.innerWidth
    });
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initSidebarToggle: true };
}

