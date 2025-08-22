<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Notifikasi SIMAMANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .test-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .test-button {
            margin: 0.5rem;
            min-width: 150px;
        }
        
        /* Enhanced Auto-hide notification animations */
        .alert {
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        .alert.fade {
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .alert.fade-out {
            opacity: 0;
            transform: translateY(-20px);
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Progress bar for notifications */
        .alert::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: currentColor;
            animation: notification-progress 3s linear forwards;
        }

        @keyframes notification-progress {
            from { width: 100%; }
            to { width: 0%; }
        }

        /* Hover pause animation */
        .alert:hover::after {
            animation-play-state: paused;
        }

        /* Toast animations */
        .toast {
            transition: all 0.3s ease-in-out;
        }
        
        .toast.fade {
            opacity: 0;
            transform: translateX(20px);
        }
        
        .toast.fade-out {
            opacity: 0;
            transform: translateX(50px);
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1 class="text-center mb-4">
            <i class="bi bi-bell-fill text-primary me-2"></i>
            Test Sistem Notifikasi SIMAMANG
        </h1>
        
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Halaman ini untuk menguji sistem notifikasi yang baru. Semua notifikasi akan hilang otomatis dalam 3 detik.
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">Toast Notifications</h4>
                <button class="btn btn-success test-button" onclick="testToast('success')">
                    <i class="bi bi-check-circle me-2"></i>Success Toast
                </button>
                <button class="btn btn-danger test-button" onclick="testToast('error')">
                    <i class="bi bi-x-circle me-2"></i>Error Toast
                </button>
                <button class="btn btn-warning test-button" onclick="testToast('warning')">
                    <i class="bi bi-exclamation-triangle me-2"></i>Warning Toast
                </button>
                <button class="btn btn-info test-button" onclick="testToast('info')">
                    <i class="bi bi-info-circle me-2"></i>Info Toast
                </button>
            </div>
            
            <div class="col-md-6">
                <h4 class="mb-3">Alert Notifications</h4>
                <button class="btn btn-success test-button" onclick="testAlert('success')">
                    <i class="bi bi-check-circle me-2"></i>Success Alert
                </button>
                <button class="btn btn-danger test-button" onclick="testAlert('error')">
                    <i class="bi bi-x-circle me-2"></i>Error Alert
                </button>
                <button class="btn btn-warning test-button" onclick="testAlert('warning')">
                    <i class="bi bi-exclamation-triangle me-2"></i>Warning Alert
                </button>
                <button class="btn btn-info test-button" onclick="testAlert('info')">
                    <i class="bi bi-info-circle me-2"></i>Info Alert
                </button>
            </div>
        </div>
        
        <hr class="my-4">
        
        <div class="row">
            <div class="col-md-6">
                <h4 class="mb-3">Custom Duration</h4>
                <button class="btn btn-primary test-button" onclick="testCustomDuration()">
                    <i class="bi bi-clock me-2"></i>5 Detik Duration
                </button>
                <button class="btn btn-secondary test-button" onclick="testMultiple()">
                    <i class="bi bi-collection me-2"></i>Multiple Notifications
                </button>
            </div>
            
            <div class="col-md-6">
                <h4 class="mb-3">Backward Compatibility</h4>
                <button class="btn btn-outline-success test-button" onclick="testLegacy()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Legacy Functions
                </button>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-light rounded">
            <h5><i class="bi bi-lightbulb me-2"></i>Fitur yang Diuji:</h5>
            <ul class="mb-0">
                <li>✅ Auto-hide dalam 3 detik</li>
                <li>✅ Animasi smooth fade-out</li>
                <li>✅ Progress bar visual</li>
                <li>✅ Hover pause animation</li>
                <li>✅ Multiple notification support</li>
                <li>✅ Backward compatibility</li>
            </ul>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 2001;"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Notification System -->
    <script src="public/assets/js/notification-system.js"></script>
    
    <script>
        // Test functions
        function testToast(type) {
            const messages = {
                'success': 'Data berhasil disimpan!',
                'error': 'Terjadi kesalahan sistem!',
                'warning': 'Password terlalu lemah!',
                'info': 'Memproses data...'
            };
            
            if (window.notifications) {
                window.notifications.showToast(messages[type], type);
            } else {
                // Fallback
                alert(messages[type]);
            }
        }
        
        function testAlert(type) {
            const messages = {
                'success': 'Data berhasil disimpan!',
                'error': 'Terjadi kesalahan sistem!',
                'warning': 'Password terlalu lemah!',
                'info': 'Memproses data...'
            };
            
            if (window.notifications) {
                window.notifications.showAlert(messages[type], type);
            } else {
                // Fallback
                alert(messages[type]);
            }
        }
        
        function testCustomDuration() {
            if (window.notifications) {
                window.notifications.showToast('Notifikasi dengan durasi 5 detik', 'info', 5000);
            }
        }
        
        function testMultiple() {
            if (window.notifications) {
                window.notifications.showToast('Notifikasi 1', 'success');
                setTimeout(() => window.notifications.showToast('Notifikasi 2', 'warning'), 500);
                setTimeout(() => window.notifications.showToast('Notifikasi 3', 'error'), 1000);
            }
        }
        
        function testLegacy() {
            // Test backward compatibility
            if (typeof showToast === 'function') {
                showToast('Legacy showToast function', 'success');
            }
            if (typeof showNotification === 'function') {
                showNotification('Legacy showNotification function', 'warning');
            }
        }
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Notification system test page loaded');
            console.log('Available functions:', {
                notifications: !!window.notifications,
                showToast: typeof showToast,
                showNotification: typeof showNotification
            });
        });
    </script>
</body>
</html>

