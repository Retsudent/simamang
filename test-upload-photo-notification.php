<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Upload Photo Notification</title>
    
    <!-- Bootstrap 5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons 1.11.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            padding: 2rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .test-section {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
        }
        
        /* Toast Container */
        #toastContainer {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 11000;
            max-width: 380px;
        }
        
        .toast {
            margin: 0.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .toast.success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border-left: 4px solid #10b981;
        }
        
        .toast.error {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .toast-header {
            background: transparent;
            border-bottom: none;
            padding: 0.75rem 1rem 0.25rem;
        }
        
        .toast-body {
            padding: 0.25rem 1rem 0.75rem;
        }
    </style>
</head>
<body>
    <h1>Test Upload Photo Notification</h1>
    
    <div class="test-section">
        <h3>Test Upload Photo Form</h3>
        <p>Form ini mensimulasikan upload foto profil dengan notifikasi:</p>
        
        <form id="testUploadForm" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="foto_profil" class="form-label">Pilih Foto</label>
                <input type="file" class="form-control" id="foto_profil" name="foto_profil" accept="image/*">
                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
            </div>
            <button type="submit" class="btn btn-primary">Upload Foto</button>
        </form>
    </div>
    
    <div class="test-section">
        <h3>Test Manual Notifications</h3>
        <button class="btn btn-success" onclick="showSuccessNotification()">Show Success Notification</button>
        <button class="btn btn-danger" onclick="showErrorNotification()">Show Error Notification</button>
    </div>
    
    <!-- Toast Container -->
    <div id="toastContainer"></div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple notification system for testing
        function showToast(message, type = 'success', duration = 3000) {
            const toastContainer = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast ${type} show`;
            toast.style.transition = 'all 0.3s ease-in-out';
            
            toast.innerHTML = `
                <div class="toast-header">
                    <i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'exclamation-circle-fill'} me-2"></i>
                    <strong class="me-auto">${type === 'success' ? 'Berhasil' : 'Error'}</strong>
                    <button type="button" class="btn-close" onclick="this.closest('.toast').remove()"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto-hide after duration
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, duration);
        }
        
        function showSuccessNotification() {
            showToast('Foto profil berhasil diperbarui', 'success');
        }
        
        function showErrorNotification() {
            showToast('Gagal mengupload foto: File terlalu besar', 'error');
        }
        
        // Handle form submission
        document.getElementById('testUploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fileInput = document.getElementById('foto_profil');
            const file = fileInput.files[0];
            
            if (!file) {
                showToast('Pilih file foto yang valid', 'error');
                return;
            }
            
            // Simulate upload process
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Uploading...';
            submitBtn.disabled = true;
            
            // Simulate server response after 2 seconds
            setTimeout(() => {
                // Simulate success (90% chance) or error (10% chance)
                const isSuccess = Math.random() > 0.1;
                
                if (isSuccess) {
                    showToast('Foto profil berhasil diperbarui', 'success');
                } else {
                    showToast('Gagal mengupload foto: File terlalu besar', 'error');
                }
                
                // Reset form and button
                this.reset();
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
        
        // Test notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Test page loaded successfully');
            console.log('Available functions:');
            console.log('- showSuccessNotification()');
            console.log('- showErrorNotification()');
            console.log('- showToast(message, type, duration)');
        });
    </script>
</body>
</html>

