<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Kesalahan Sistem | SIMAMANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .error-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
        }
        
        .error-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .error-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .error-body {
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .btn-custom {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
        }
        
        .quick-links {
            margin-top: 2rem;
        }
        
        .quick-links a {
            display: inline-block;
            margin: 0.5rem;
            padding: 8px 16px;
            background: #f8f9fa;
            color: #666;
            text-decoration: none;
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        
        .quick-links a:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .help-section {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .status-section {
            background: #f3e5f5;
            border: 1px solid #e1bee7;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        @media (max-width: 768px) {
            .error-container {
                padding: 1rem;
            }
            
            .error-card {
                margin: 1rem;
            }
            
            .error-header {
                padding: 2rem 1rem;
            }
            
            .error-body {
                padding: 2rem 1rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-header">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h1 class="mb-0">System Error</h1>
                <p class="mb-0">Terjadi kesalahan pada sistem</p>
            </div>
            
            <div class="error-body">
                <h2 class="error-title">Kesalahan Sistem Terdeteksi</h2>
                <p class="error-message">
                    Maaf, sistem mengalami masalah teknis. Tim pengembang telah diberitahu 
                    dan sedang bekerja untuk memperbaikinya. Silakan coba lagi dalam beberapa saat.
                </p>
                
                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mb-4">
                    <a href="<?= base_url('/') ?>" class="btn btn-custom btn-primary-custom">
                        <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                    </a>
                    <a href="javascript:history.back()" class="btn btn-custom btn-outline-custom">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button onclick="location.reload()" class="btn btn-custom btn-outline-custom">
                        <i class="fas fa-redo mr-2"></i>Refresh
                    </button>
                </div>
                
                <!-- Quick Links -->
                <div class="quick-links">
                    <h6 class="text-muted mb-3">Link Cepat:</h6>
                    <a href="<?= base_url('login') ?>">Login</a>
                    <a href="<?= base_url('register') ?>">Daftar</a>
                    <a href="<?= base_url('siswa/dashboard') ?>">Dashboard Siswa</a>
                    <a href="<?= base_url('pembimbing/dashboard') ?>">Dashboard Pembimbing</a>
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard Admin</a>
                </div>
                
                <!-- Help Section -->
                <div class="help-section">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Mengatasi Masalah:
                    </h6>
                    <ul class="text-muted mb-3 text-left">
                        <li>Refresh halaman dan coba lagi</li>
                        <li>Pastikan koneksi internet stabil</li>
                        <li>Hapus cache browser dan cookies</li>
                        <li>Coba gunakan browser yang berbeda</li>
                        <li>Tunggu beberapa menit dan coba lagi</li>
                    </ul>
                </div>
                
                <!-- Status Section -->
                <div class="status-section">
                    <h6 class="text-purple mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Status Sistem:
                    </h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h5 mb-0 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <small class="text-muted">Database</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-0 text-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <small class="text-muted">Application</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-0 text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <small class="text-muted">Network</small>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-headset mr-2"></i>Butuh Bantuan?
                    </h6>
                    <p class="text-muted small mb-2">
                        Jika masalah berlanjut, silakan hubungi administrator sistem.
                    </p>
                    <div class="text-center">
                        <small class="text-muted">
                            <strong>Error ID:</strong> <?= uniqid() ?> | 
                            <strong>Time:</strong> <?= date('Y-m-d H:i:s') ?> |
                            <strong>Environment:</strong> Production
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add some animation
        document.addEventListener('DOMContentLoaded', function() {
            const errorCard = document.querySelector('.error-card');
            errorCard.style.opacity = '0';
            errorCard.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                errorCard.style.transition = 'all 0.6s ease';
                errorCard.style.opacity = '1';
                errorCard.style.transform = 'translateY(0)';
            }, 100);
        });
        
        // Auto-refresh after 60 seconds
        setTimeout(() => {
            if (confirm('Halaman akan di-refresh otomatis untuk memeriksa status sistem. Lanjutkan?')) {
                location.reload();
            }
        }, 60000);
        
        // Check system status every 30 seconds
        setInterval(() => {
            // You can implement AJAX call to check system status here
            console.log('Checking system status...');
        }, 30000);
    </script>
</body>
</html>
