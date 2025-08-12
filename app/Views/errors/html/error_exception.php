<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Kesalahan Sistem | SIMAMANG</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet>
    
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
            max-width: 800px;
            width: 100%;
        }
        
        .error-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
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
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .error-message {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
            text-align: center;
        }
        
        .error-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-left: 4px solid #ff6b6b;
        }
        
        .error-code {
            font-family: 'Courier New', monospace;
            background: #e9ecef;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            margin: 0.5rem 0;
            word-break: break-all;
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
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border: none;
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid #ff6b6b;
            color: #ff6b6b;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #ff6b6b;
            color: white;
        }
        
        .stack-trace {
            background: #2d3748;
            color: #e2e8f0;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1rem 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            max-height: 300px;
            overflow-y: auto;
            border-left: 4px solid #ff6b6b;
        }
        
        .stack-trace::-webkit-scrollbar {
            width: 8px;
        }
        
        .stack-trace::-webkit-scrollbar-track {
            background: #4a5568;
            border-radius: 4px;
        }
        
        .stack-trace::-webkit-scrollbar-thumb {
            background: #ff6b6b;
            border-radius: 4px;
        }
        
        .quick-links {
            margin-top: 2rem;
            text-align: center;
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
            background: #ff6b6b;
            color: white;
            transform: translateY(-2px);
        }
        
        .help-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
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
            
            .stack-trace {
                font-size: 0.8rem;
                padding: 1rem;
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
                    dan sedang bekerja untuk memperbaikinya.
                </p>
                
                <!-- Error Details -->
                <div class="error-details">
                    <h6 class="text-danger mb-3">
                        <i class="fas fa-bug mr-2"></i>Detail Kesalahan:
                    </h6>
                    
                    <?php if (ENVIRONMENT !== 'production'): ?>
                        <?php if (isset($message)): ?>
                            <div class="mb-3">
                                <strong>Message:</strong>
                                <div class="error-code"><?= esc($message) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($code)): ?>
                            <div class="mb-3">
                                <strong>Error Code:</strong>
                                <div class="error-code"><?= esc($code) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($file)): ?>
                            <div class="mb-3">
                                <strong>File:</strong>
                                <div class="error-code"><?= esc($file) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($line)): ?>
                            <div class="mb-3">
                                <strong>Line:</strong>
                                <div class="error-code"><?= esc($line) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($trace)): ?>
                            <div class="mb-3">
                                <strong>Stack Trace:</strong>
                                <div class="stack-trace"><?= esc($trace) ?></div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-muted mb-0">
                            Detail kesalahan tidak ditampilkan dalam mode production untuk keamanan.
                        </p>
                    <?php endif; ?>
                </div>
                
                <!-- Action Buttons -->
                <div class="text-center mb-4">
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="<?= base_url('/') ?>" class="btn btn-custom btn-primary-custom">
                            <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                        </a>
                        <a href="javascript:history.back()" class="btn btn-custom btn-outline-custom">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button onclick="location.reload()" class="btn btn-custom btn-outline-custom">
                            <i class="fas fa-redo mr-2"></i>Refresh Halaman
                        </button>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="quick-links">
                    <h6 class="text-muted mb-3">Link Cepat:</h6>
                    <a href="<?= base_url('login') ?>">Login</a>
                    <a href="<?= base_url('siswa/dashboard') ?>">Dashboard Siswa</a>
                    <a href="<?= base_url('pembimbing/dashboard') ?>">Dashboard Pembimbing</a>
                    <a href="<?= base_url('admin/dashboard') ?>">Dashboard Admin</a>
                </div>
                
                <!-- Help Section -->
                <div class="help-section">
                    <h6 class="text-warning mb-2">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Mengatasi Masalah:
                    </h6>
                    <ul class="text-muted mb-3">
                        <li>Refresh halaman dan coba lagi</li>
                        <li>Pastikan koneksi internet stabil</li>
                        <li>Hapus cache browser dan cookies</li>
                        <li>Coba gunakan browser yang berbeda</li>
                        <li>Hubungi administrator jika masalah berlanjut</li>
                    </ul>
                    
                    <div class="text-center">
                        <small class="text-muted">
                            <strong>Error ID:</strong> <?= uniqid() ?> | 
                            <strong>Time:</strong> <?= date('Y-m-d H:i:s') ?> |
                            <strong>Environment:</strong> <?= ENVIRONMENT ?>
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
        
        // Auto-refresh after 30 seconds (optional)
        setTimeout(() => {
            if (confirm('Halaman akan di-refresh otomatis. Lanjutkan?')) {
                location.reload();
            }
        }, 30000);
    </script>
</body>
</html>
