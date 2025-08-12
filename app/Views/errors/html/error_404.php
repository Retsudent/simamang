<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SIMAMANG</title>
    
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
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
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
        
        .error-code {
            font-size: 6rem;
            font-weight: bold;
            color: #4e73df;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .error-title {
            font-size: 2rem;
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
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            color: white;
        }
        
        .btn-outline-custom {
            border: 2px solid #4e73df;
            color: #4e73df;
            background: transparent;
        }
        
        .btn-outline-custom:hover {
            background: #4e73df;
            color: white;
        }
        
        .search-box {
            background: #f8f9fa;
            border-radius: 50px;
            padding: 15px 25px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .search-box:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
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
            background: #4e73df;
            color: white;
            transform: translateY(-2px);
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
            
            .error-code {
                font-size: 4rem;
            }
            
            .error-title {
                font-size: 1.5rem;
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
                <h1 class="mb-0">Oops!</h1>
                <p class="mb-0">Halaman yang Anda cari tidak ditemukan</p>
            </div>
            
            <div class="error-body">
                <div class="error-code">404</div>
                <h2 class="error-title">Halaman Tidak Ditemukan</h2>
                <p class="error-message">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan. 
                    Halaman mungkin telah dipindahkan, dihapus, atau URL yang Anda masukkan salah.
                </p>
                
                <!-- Search Box -->
                <div class="search-container">
                    <input type="text" class="form-control search-box" 
                           placeholder="Cari halaman atau fitur..." 
                           id="searchInput">
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center mb-4">
                    <a href="<?= base_url('/') ?>" class="btn btn-custom btn-primary-custom">
                        <i class="fas fa-home mr-2"></i>Beranda
                    </a>
                    <a href="javascript:history.back()" class="btn btn-custom btn-outline-custom">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
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
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="text-primary mb-2">
                        <i class="fas fa-question-circle mr-2"></i>Butuh Bantuan?
                    </h6>
                    <p class="text-muted small mb-2">
                        Jika Anda yakin ini adalah kesalahan sistem, silakan hubungi administrator.
                    </p>
                    <p class="text-muted small mb-0">
                        <strong>Error Code:</strong> 404 | 
                        <strong>URL:</strong> <?= current_url() ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    // You can implement search functionality here
                    alert('Fitur pencarian akan segera tersedia. Silakan gunakan menu navigasi.');
                }
            }
        });
        
        // Auto-focus search box
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchInput').focus();
        });
        
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
    </script>
</body>
</html>
